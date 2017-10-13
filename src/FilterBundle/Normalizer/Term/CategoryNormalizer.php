<?php

namespace BestIt\Commercetools\FilterBundle\Normalizer\Term;

use BestIt\Commercetools\FilterBundle\Enum\CategoryFacetFilterType;
use BestIt\Commercetools\FilterBundle\Enum\FacetType;
use BestIt\Commercetools\FilterBundle\Exception\SkipTermException;
use BestIt\Commercetools\FilterBundle\Model\Facet\FacetConfig;
use BestIt\Commercetools\FilterBundle\Model\Search\SearchContext;
use BestIt\Commercetools\FilterBundle\Model\Term\Term;
use BestIt\Commercetools\FilterBundle\Normalizer\TermNormalizerInterface;
use Commercetools\Commons\Helper\QueryHelper;
use Commercetools\Core\Client;
use Commercetools\Core\Model\Category\Category;
use Commercetools\Core\Model\Category\CategoryReference;
use Commercetools\Core\Request\Categories\CategoryQueryRequest;
use Psr\Cache\CacheItemPoolInterface;

/**
 * Class CategoryNormalizer
 *
 * @author Michel Chowanski <chowanski@bestit-online.de>
 * @package BestIt\Commercetools\FilterBundle\Normalizer\Term
 */
class CategoryNormalizer implements TermNormalizerInterface
{
    /**
     * @var string CACHE_KEY
     */
    const CACHE_KEY = 'best_it_commercetools_filter.normalizer_term.category_normalizer';

    /**
     * Cache pool
     *
     * @var CacheItemPoolInterface
     */
    private $cacheItemPool;

    /**
     * Cache time value.
     *
     * @var int $cacheTime
     */
    private $cacheTime;

    /**
     * CommerceTools client.
     *
     * @var Client
     */
    private $client;

    /**
     * The query helper
     *
     * @var QueryHelper
     */
    private $queryHelper;

    /**
     * The facet filer type
     * @var string
     */
    private $facetFilterType;

    /**
     * Memory cached labels
     *
     * @var array
     */
    private $labels = [];

    /**
     * CategoryNormalizer constructor.
     *
     * @param Client $client
     * @param CacheItemPoolInterface $cacheItemPool
     * @param int $cacheTime
     * @param QueryHelper $queryHelper
     * @param string $facetFilterType
     */
    public function __construct(
        Client $client,
        CacheItemPoolInterface $cacheItemPool,
        int $cacheTime,
        QueryHelper $queryHelper,
        string $facetFilterType
    ) {
        $this->client = $client;
        $this->cacheItemPool = $cacheItemPool;
        $this->cacheTime = $cacheTime;
        $this->queryHelper = $queryHelper;
        $this->facetFilterType = $facetFilterType;
    }

    /**
     * {@inheritdoc}
     */
    public function normalize(FacetConfig $config, Term $term, SearchContext $context): Term
    {
        $cacheItem = $this->cacheItemPool->getItem(self::CACHE_KEY);
        $id = $term->getTitle();

        // Load from cache when memory cache is empty
        if (count($this->labels) === 0 && $cacheItem->isHit()) {
            $this->labels = $cacheItem->get();
        }

        // Fetch all categories if label is missing and save new cache
        if (!array_key_exists($id, $this->labels)) {
            $this->fetchCategories();

            $cacheItem->set($this->labels);
            $cacheItem->expiresAfter($this->cacheTime);
            $this->cacheItemPool->save($cacheItem);
        }

        // Only show child categories in category listing
        if ($category = $context->getCategory()) {
            switch ($this->facetFilterType) {
                case CategoryFacetFilterType::PARENT:
                    if ($category->getId() !== $this->labels[$id]['parent']) {
                        throw new SkipTermException(sprintf(
                            'Category %s is not child from %s',
                            $id,
                            $category->getId()
                        ));
                    }

                    break;

                case CategoryFacetFilterType::ANCESTORS:
                    if (!in_array($category->getId(), $this->labels[$id]['ancestors'], true)) {
                        throw new SkipTermException(sprintf(
                            'Category %s is no ancestor from %s',
                            $category->getId(),
                            $id
                        ));
                    }

                    break;

                default:
                    // We filter nothing
            }
        }

        // Save value
        $term->setTitle($this->labels[$id]['name'] ?? $id);

        return $term;
    }

    /**
     * Fetch all categories from commercetools.
     *
     * @return void
     */
    private function fetchCategories()
    {
        $request = CategoryQueryRequest::of();

        /** @var Category $category */
        foreach ($this->queryHelper->getAll($this->client, $request) as $category) {
            $name = $category->getName() ? $category->getName()->getLocalized() : $category->getId();
            $this->labels[$category->getId()] = [
                'name' => $name,
                'parent' => ($parent = $category->getParent()) ? $parent->getId() : 0,
                'ancestors' => array_map(function (CategoryReference $reference) {
                    return $reference->getId();
                }, iterator_to_array($category->getAncestors()))
            ];
        }
    }

    /**
     * {@inheritdoc}
     */
    public function support(string $type): bool
    {
        return $type === FacetType::CATEGORY;
    }
}

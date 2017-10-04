<?php

namespace BestIt\Commercetools\FilterBundle\Normalizer\Term;

use BestIt\Commercetools\FilterBundle\Model\Facet\FacetConfig;
use BestIt\Commercetools\FilterBundle\Model\Term\Term;
use BestIt\Commercetools\FilterBundle\Normalizer\TermNormalizerInterface;
use Commercetools\Commons\Helper\QueryHelper;
use Commercetools\Core\Client;
use Commercetools\Core\Model\Category\Category;
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
     */
    public function __construct(
        Client $client,
        CacheItemPoolInterface $cacheItemPool,
        int $cacheTime,
        QueryHelper $queryHelper
    ) {
        $this->client = $client;
        $this->cacheItemPool = $cacheItemPool;
        $this->cacheTime = $cacheTime;
        $this->queryHelper = $queryHelper;
    }

    /**
     * {@inheritdoc}
     */
    public function normalize(FacetConfig $config, Term $term): Term
    {
        $cacheItem = $this->cacheItemPool->getItem(self::CACHE_KEY);

        // Load from cache when memory cache is empty
        if (count($this->labels) === 0 && $cacheItem->isHit()) {
            $this->labels = $cacheItem->get();
        }

        // Fetch all categories if label is missing and save new cache
        if (!array_key_exists($term->getTitle(), $this->labels)) {
            $this->fetchCategories();

            $cacheItem->set($this->labels);
            $cacheItem->expiresAfter($this->cacheTime);
            $this->cacheItemPool->save($cacheItem);
        }

        // Save value
        $term->setTitle($this->labels[$term->getTitle()] ?? $term->getTitle());

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
            $this->labels[$category->getId()] = $name;
        }
    }
}

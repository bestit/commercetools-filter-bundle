<?php

namespace BestIt\Commercetools\FilterBundle\Normalizer\Term;

use BestIt\Commercetools\FilterBundle\Model\Facet\FacetConfig;
use BestIt\Commercetools\FilterBundle\Model\Term\Term;
use BestIt\Commercetools\FilterBundle\Normalizer\TermNormalizerInterface;
use Commercetools\Commons\Helper\QueryHelper;
use Commercetools\Core\Client;
use Commercetools\Core\Error\ApiException;
use Commercetools\Core\Model\ProductType\AttributeDefinition;
use Commercetools\Core\Model\ProductType\ProductType;
use Commercetools\Core\Model\ProductType\ProductTypeCollection;
use Commercetools\Core\Model\ProductType\EnumType;
use Commercetools\Core\Model\ProductType\LocalizedEnumType;
use Commercetools\Core\Model\ProductType\SetType;
use Commercetools\Core\Request\ProductTypes\ProductTypeQueryRequest;
use Exception;
use Psr\Cache\CacheItemPoolInterface;

/**
 * Helper to get (localized) labels for (l)enum attributes.
 *
 * @package BestIt\Commercetools\FilterBundle\Normalizer\Term
 * @author Tim Kellner <tim.kellner@bestit-online.de>
 */
class EnumAttributeNormalizer implements TermNormalizerInterface
{
    /**
     * @var string CACHE_KEY
     */
    const CACHE_KEY = 'best_it_commercetools_filter.normalizer_term.enum_attribute_normalizer';

    /**
     * Cache to prevent unnecessary
     * @var CacheItemPoolInterface
     */
    private $cacheItemPool;

    /**
     * Cache time value.
     * @var int $cacheTime
     */
    private $cacheTime;

    /**
     * @var Client CommerceTools client.
     */
    private $client;

    /**
     * The query helper
     *
     * @var QueryHelper
     */
    private $queryHelper;

    /**
     * List of loaded labels.
     *
     * @var array[string[]] $labels
     */
    private $labels = [];

    /**
     * Array of commerce tools product types.
     *
     * @var ProductType[] $productTypes
     */
    private $productTypes;

    /**
     * EnumAttributeNormalizer constructor.
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
        // Collect labels if not already cached
        if (!array_key_exists($config->getField(), $this->labels)) {
            $this->labels[$config->getField()] = $this->getLabels($config->getField());
        }

        // Set label if exists
        if (array_key_exists($term->getTitle(), $this->labels[$config->getField()])) {
            $term->setTitle($this->labels[$config->getField()][$term->getTitle()]);
        }

        return $term;
    }

    /**
     * Get a key value list for enum.
     *
     * @param string $attributeName Name of attribute.
     *
     * @return string[]
     * @throws Exception
     * @throws ApiException
     */
    private function getLabels(string $attributeName): array
    {
        if (!array_key_exists($attributeName, $this->labels)) {
            $this->labels[$attributeName] = [];

            $cacheItem = $this->cacheItemPool->getItem(self::CACHE_KEY . '-' . md5($attributeName));

            if ($cacheItem->isHit()) {
                $this->labels[$attributeName] = $cacheItem->get();
            } else {
                /**
                 * @var ProductType $productType
                 */
                foreach ($this->getProductTypes() as $productType) {
                    $attribute = $productType->getAttributes()->getByName($attributeName);

                    if ($attribute instanceof AttributeDefinition) {
                        $type = $attribute->getType();

                        // "Set" is just a type which contains multiple values from a real type (like enum)
                        if ($type instanceof SetType) {
                            $type = $type->getElementType();
                        }

                        if ($type instanceof LocalizedEnumType) {
                            $values = $type->getValues();

                            foreach ($values as $value) {
                                $this->labels[$attributeName][$value->getKey()] = $value->getLabel()->getLocalized();
                            }
                        } elseif ($type instanceof EnumType) {
                            $values = $type->getValues();

                            foreach ($values as $value) {
                                $this->labels[$attributeName][$value->getKey()] = $value->getLabel();
                            }
                        }
                    }
                }

                $cacheItem->set($this->labels[$attributeName]);
                $cacheItem->expiresAfter($this->cacheTime);
                $this->cacheItemPool->save($cacheItem);
            }
        }

        return $this->labels[$attributeName];
    }

    /**
     * Get all product types from commercetools.
     *
     * @return ProductType[]
     */
    private function getProductTypes(): array
    {
        if (!is_array($this->productTypes)) {
            $this->productTypes = [];

            $request = ProductTypeQueryRequest::of();
            $this->productTypes = iterator_to_array($this->queryHelper->getAll($this->client, $request));
        }

        return $this->productTypes;
    }
}

<?php

namespace BestIt\Commercetools\FilterBundle\Helper;

use Commercetools\Core\Client;
use Commercetools\Core\Error\ApiException;
use Commercetools\Core\Model\ProductType\AttributeDefinition;
use Commercetools\Core\Model\ProductType\ProductType;
use Commercetools\Core\Model\ProductType\ProductTypeCollection;
use Commercetools\Core\Model\ProductType\EnumType;
use Commercetools\Core\Model\ProductType\LocalizedEnumType;
use Commercetools\Core\Request\ProductTypes\ProductTypeQueryRequest;
use Commercetools\Core\Response\PagedQueryResponse;
use Exception;
use Psr\Cache\CacheItemPoolInterface;

/**
 * Helper to get (localized) labels for (l)enum attributes.
 *
 * @package BestIt\Commercetools\FilterBundle\Helper
 * @author Tim Kellner <tim.kellner@bestit-online.de>
 */
class EnumAttributeHelper
{
    /**
     * @var string CACHE_KEY
     */
    const CACHE_KEY = 'bestit.commercetools.filter_bundle.helper.enum_attribute_helper';

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
     * EnumAttributeHelper constructor.
     *
     * @param Client $client CommerceTools client.
     */
    public function __construct(
        Client $client,
        CacheItemPoolInterface $cacheItemPool,
        int $cacheTime
    ) {
        $this->client = $client;
        $this->cacheItemPool = $cacheItemPool;
        $this->cacheTime = $cacheTime;
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
    public function getLabels(string $attributeName): array
    {
        if (!array_key_exists($attributeName, $this->labels)) {
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

            do {
                $productTypeRequest = ProductTypeQueryRequest::of();
                $productTypeRequest->limit(500);

                $response = $this->client->execute($productTypeRequest);

                if ($response instanceof PagedQueryResponse) {
                    $productTypeCollection = $response->toObject();

                    if ($productTypeCollection instanceof ProductTypeCollection) {
                        foreach ($productTypeCollection as $productType) {
                            $this->productTypes[] = $productType;
                        }
                    }
                }
            } while ($response->getTotal() > ($response->getOffset() + $response->getCount()));
        }

        return $this->productTypes;
    }
}

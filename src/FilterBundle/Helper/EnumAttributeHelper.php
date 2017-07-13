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

/**
 * Helper to get (localized) labels for (l)enum attributes.
 *
 * @package BestIt\Commercetools\FilterBundle\Helper
 * @author Tim Kellner <tim.kellner@bestit-online.de>
 */
class EnumAttributeHelper
{
    /**
     * @var Client CommerceTools client.
     */
    private $client;

    /**
     * EnumAttributeHelper constructor.
     *
     * @param Client $client CommerceTools client.
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
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
        $productTypeRequest = ProductTypeQueryRequest::of();

        $response = $this->client->execute($productTypeRequest);

        $labels = [];

        if ($response instanceof PagedQueryResponse) {
            $productTypeCollection = $response->toObject();

            if ($productTypeCollection instanceof ProductTypeCollection) {
                /**
                 * @var ProductType $productType
                 */
                foreach ($productTypeCollection as $productType) {
                    $attribute = $productType->getAttributes()->getByName($attributeName);
                    
                    if ($attribute instanceof AttributeDefinition) {
                        $type = $attribute->getType();

                        if ($type instanceof LocalizedEnumType) {
                            $values = $type->getValues();

                            foreach ($values as $value) {
                                $labels[$value->getKey()] = $value->getLabel()->getLocalized();
                            }
                        } elseif ($type instanceof EnumType) {
                            $values = $type->getValues();

                            foreach ($values as $value) {
                                $labels[$value->getKey()] = $value->getLabel();
                            }
                        }
                    }
                }
            }
        }

        return $labels;
    }
}

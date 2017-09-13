<?php

namespace BestIt\Commercetools\FilterBundle\Model\Suggest;

use Commercetools\Core\Response\ApiResponseInterface;

/**
 * Result data for suggest
 *
 * @author     chowanski <chowanski@bestit-online.de>
 * @package    BestIt\Commercetools\FilterBundle
 * @subpackage Model\Suggest
 */
class SuggestResult
{
    /**
     * Matched products
     *
     * @var array
     */
    private $products = [];

    /**
     * The origin response from commercetools
     *
     * @var ApiResponseInterface
     */
    private $httpResponse;

    /**
     * Get products
     *
     * @return array
     */
    public function getProducts(): array
    {
        return $this->products;
    }

    /**
     * Set products
     *
     * @param array $products
     * @return SuggestResult
     */
    public function setProducts(array $products): SuggestResult
    {
        $this->products = $products;
        return $this;
    }

    /**
     * Get httpResponse
     *
     * @return ApiResponseInterface
     */
    public function getHttpResponse(): ApiResponseInterface
    {
        return $this->httpResponse;
    }

    /**
     * Set httpResponse
     *
     * @param ApiResponseInterface $httpResponse
     * @return SuggestResult
     */
    public function setHttpResponse(ApiResponseInterface $httpResponse = null): SuggestResult
    {
        $this->httpResponse = $httpResponse;
        return $this;
    }
}

<?php

namespace BestIt\Commercetools\FilterBundle\Model\Suggest;

use Commercetools\Core\Model\Category\Category;
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
     * @var Category $baseCategory
     */
    private $baseCategory;

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
     * Get the category
     *
     * @return Category
     */
    public function getBaseCategory(): Category
    {
        return $this->baseCategory;
    }

    /**
     * Checks if base category is set
     *
     * @return bool
     */
    public function hasBaseQuery(): bool
    {
        return $this->baseCategory !== null;
    }

    /**
     * Set the category
     *
     * @param Category $baseCategory
     * @return SuggestResult
     */
    public function setBaseCategory(Category $baseCategory): SuggestResult
    {
        $this->baseCategory = $baseCategory;
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

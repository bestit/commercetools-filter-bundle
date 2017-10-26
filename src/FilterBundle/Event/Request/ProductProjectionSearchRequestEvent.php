<?php

namespace BestIt\Commercetools\FilterBundle\Event\Request;

use Commercetools\Core\Request\Products\ProductProjectionSearchRequest;
use Symfony\Component\EventDispatcher\Event;

/**
 * Event for products search requests
 *
 * @author     chowanski <chowanski@bestit-online.de>
 * @package    BestIt\Commercetools\FilterBundle
 * @subpackage Event\Request
 */
class ProductProjectionSearchRequestEvent extends Event
{
    /**
     * Products search request
     *
     * @var ProductProjectionSearchRequest
     */
    private $request;

    /**
     * The optional search keywords
     *
     * @var null|string
     */
    private $searchKeyword;

    /**
     * ProductProjectionSearchRequestEvent constructor.
     *
     * @param ProductProjectionSearchRequest $request
     * @param string|null $searchKeyword
     */
    public function __construct(ProductProjectionSearchRequest $request, string $searchKeyword = null)
    {
        $this->request = $request;
        $this->searchKeyword = $searchKeyword;
    }

    /**
     * Get searchKeyword
     *
     * @return null|string
     */
    public function getSearchKeyword()
    {
        return $this->searchKeyword;
    }

    /**
     * Get request
     *
     * @return ProductProjectionSearchRequest
     */
    public function getRequest(): ProductProjectionSearchRequest
    {
        return $this->request;
    }

    /**
     * Set request
     *
     * @param ProductProjectionSearchRequest $request
     *
     * @return ProductProjectionSearchRequestEvent
     */
    public function setRequest(ProductProjectionSearchRequest $request): ProductProjectionSearchRequestEvent
    {
        $this->request = $request;

        return $this;
    }
}

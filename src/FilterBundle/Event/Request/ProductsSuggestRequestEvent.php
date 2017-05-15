<?php

namespace BestIt\Commercetools\FilterBundle\Event\Request;

use Commercetools\Core\Request\Products\ProductsSuggestRequest;
use Symfony\Component\EventDispatcher\Event;

/**
 * Event for products suggest requests
 *
 * @author     chowanski <chowanski@bestit-online.de>
 * @package    BestIt\Commercetools\FilterBundle
 * @subpackage Event\Request
 */
class ProductsSuggestRequestEvent extends Event
{
    /**
     * Products suggest request
     *
     * @var ProductsSuggestRequest
     */
    private $request;

    /**
     * ProductsSuggestRequestEvent constructor.
     *
     * @param ProductsSuggestRequest $request
     */
    public function __construct(ProductsSuggestRequest $request)
    {
        $this->setRequest($request);
    }

    /**
     * Get request
     *
     * @return ProductsSuggestRequest
     */
    public function getRequest(): ProductsSuggestRequest
    {
        return $this->request;
    }

    /**
     * Set request
     *
     * @param ProductsSuggestRequest $request
     *
     * @return ProductsSuggestRequestEvent
     */
    public function setRequest(ProductsSuggestRequest $request): ProductsSuggestRequestEvent
    {
        $this->request = $request;
        
        return $this;
    }
}

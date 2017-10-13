<?php

namespace BestIt\Commercetools\FilterBundle;

/**
 * Events for filter
 *
 * @author  chowanski <chowanski@bestit-online.de>
 * @package BestIt\Commercetools\FilterBundle
 * @version $id$
 */
class FilterEvent
{
    /**
     * Post event for filter products request
     *
     * @Event("BestIt\Commercetools\FilterBundle\Event\Request\ProductProjectionSearchRequestEvent")
     */
    const PRODUCTS_REQUEST_POST = 'best_it_commercetools_filter.event.filter.products.request.post';
}

<?php

namespace BestIt\Commercetools\FilterBundle;

/**
 * Events for suggests
 *
 * @author  chowanski <chowanski@bestit-online.de>
 * @package BestIt\Commercetools\FilterBundle
 * @version $id$
 */
class SuggestEvent
{
    /**
     * Post event for suggest keywords request
     *
     * @Event("BestIt\Commercetools\FilterBundle\Event\Request\ProductsSuggestRequestEvent")
     */
    const KEYWORDS_REQUEST_POST = 'best_it_commercetools_filter.event.suggest.keywords.request.post';

    /**
     * Post event for suggest products request
     *
     * @Event("BestIt\Commercetools\FilterBundle\Event\Request\ProductProjectionSearchRequestEvent")
     */
    const PRODUCTS_REQUEST_POST = 'best_it_commercetools_filter.event.suggest.products.request.post';
}

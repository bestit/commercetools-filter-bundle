<?php

namespace BestIt\Commercetools\FilterBundle\Tests\Unit;

use BestIt\Commercetools\FilterBundle\SuggestEvent;
use PHPUnit\Framework\TestCase;

/**
 * Test suggest events
 *
 * @author   chowanski <chowanski@bestit-online.de>
 * @category Tests\Unit
 * @package  BestIt\Commercetools\FilterBundle
 * @version  $id$
 */
class SuggestEventTest extends TestCase
{
    /**
     * Test keywords request post event
     *
     * @return void
     */
    public function testKeywordsRequestPost()
    {
        static::assertSame(
            'best_it_commercetools_filter.event.suggest.keywords.request.post',
            SuggestEvent::KEYWORDS_REQUEST_POST
        );
    }

    /**
     * Test products request post event
     *
     * @return void
     */
    public function testProductsRequestPost()
    {
        static::assertSame(
            'best_it_commercetools_filter.event.suggest.products.request.post',
            SuggestEvent::PRODUCTS_REQUEST_POST
        );
    }
}

<?php

namespace BestIt\Commercetools\FilterBundle\Tests\Unit;

use BestIt\Commercetools\FilterBundle\FilterEvent;
use PHPUnit\Framework\TestCase;

/**
 * Test filter events
 *
 * @author   chowanski <chowanski@bestit-online.de>
 * @category Tests\Unit
 * @package  BestIt\Commercetools\FilterBundle
 * @version  $id$
 */
class FilterEventTest extends TestCase
{
    /**
     * Test products request post event
     *
     * @return void
     */
    public function testProductsRequestPost()
    {
        static::assertSame(
            'best_it_commercetools_filter.event.filter.products.request.post',
            FilterEvent::PRODUCTS_REQUEST_POST
        );
    }
}

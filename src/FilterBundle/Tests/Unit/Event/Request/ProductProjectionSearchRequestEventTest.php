<?php

namespace BestIt\Commercetools\FilterBundle\Tests\Unit\Event\Request;

use BestIt\Commercetools\FilterBundle\Event\Request\ProductProjectionSearchRequestEvent;
use Commercetools\Core\Request\Products\ProductProjectionSearchRequest;
use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\Event;

/**
 * Test for products search request event
 *
 * @author     chowanski <chowanski@bestit-online.de>
 * @category   Tests\Unit
 * @package    BestIt\Commercetools\FilterBundle
 * @subpackage Event\Request
 * @version    $id$
 */
class ProductProjectionSearchRequestEventTest extends TestCase
{
    /**
     * Test set and get request
     *
     * @return void
     */
    public function testSetAndGetRequest()
    {
        $event = new ProductProjectionSearchRequestEvent($request = new ProductProjectionSearchRequest(), 'Foobar');

        static::assertSame($request, $event->getRequest());
        static::assertSame('Foobar', $event->getSearchKeyword());

        $newRequest = new ProductProjectionSearchRequest();
        static::assertNotSame($newRequest, $event->getRequest());

        $event->setRequest($newRequest);
        static::assertSame($newRequest, $event->getRequest());
    }

    /**
     * Test that extend base event
     *
     * @return void
     */
    public function testExtendEvent()
    {
        $event = new ProductProjectionSearchRequestEvent($request = new ProductProjectionSearchRequest());

        static::assertInstanceOf(Event::class, $event);
    }
}

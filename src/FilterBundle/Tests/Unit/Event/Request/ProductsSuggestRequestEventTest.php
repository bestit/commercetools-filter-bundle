<?php

namespace BestIt\Commercetools\FilterBundle\Tests\Unit\Event\Request;

use BestIt\Commercetools\FilterBundle\Event\Request\ProductsSuggestRequestEvent;
use Commercetools\Core\Request\Products\ProductsSuggestRequest;
use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\Event;

/**
 * Test for products suggest request event
 *
 * @author     chowanski <chowanski@bestit-online.de>
 * @category   Tests\Unit
 * @package    BestIt\Commercetools\FilterBundle
 * @subpackage Event\Request
 * @version    $id$
 */
class ProductsSuggestRequestEventTest extends TestCase
{
    /**
     * Test set and get request
     *
     * @return void
     */
    public function testSetAndGetRequest()
    {
        $event = new ProductsSuggestRequestEvent($request = new ProductsSuggestRequest());

        static::assertSame($request, $event->getRequest());

        $newRequest = new ProductsSuggestRequest();
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
        $event = new ProductsSuggestRequestEvent($request = new ProductsSuggestRequest());

        static::assertInstanceOf(Event::class, $event);
    }
}

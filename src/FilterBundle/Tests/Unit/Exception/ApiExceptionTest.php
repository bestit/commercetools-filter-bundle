<?php

namespace BestIt\Commercetools\FilterBundle\Tests\Unit\Exception;

use BestIt\Commercetools\FilterBundle\Exception\ApiException;
use Exception;
use PHPUnit\Framework\TestCase;

/**
 * Test for api exception
 *
 * @author     chowanski <chowanski@bestit-online.de>
 * @category   Tests\Unit
 * @package    BestIt\Commercetools\FilterBundle
 * @subpackage Exception
 * @version    $id$
 */
class ApiExceptionTest extends TestCase
{
    /**
     * Test that exception extend from base exception
     *
     * @return void
     */
    public function testExtendBase()
    {
        static::assertInstanceOf(Exception::class, new ApiException());
    }
}

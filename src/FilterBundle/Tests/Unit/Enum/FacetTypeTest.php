<?php

namespace BestIt\Commercetools\FilterBundle\Tests\Unit\Enum;

use BestIt\Commercetools\FilterBundle\Enum\FacetType;
use PHPUnit\Framework\TestCase;

/**
 * Test for facet type enum
 *
 * @author     chowanski <chowanski@bestit-online.de>
 * @category   Tests\Unit
 * @package    BestIt\Commercetools\FilterBundle
 * @subpackage Enum
 * @version    $id$
 */
class FacetTypeTest extends TestCase
{
    /**
     * Test category value
     *
     * @return void
     */
    public function testCategoryValue()
    {
        static::assertEquals('categories', FacetType::CATEGORY);
        static::assertTrue(FacetType::isValid('categories'));
    }

    /**
     * Test enum value
     *
     * @return void
     */
    public function testEnumValue()
    {
        static::assertEquals('enum', FacetType::ENUM);
        static::assertTrue(FacetType::isValid('enum'));
    }

    /**
     * Test lenum value
     *
     * @return void
     */
    public function testLenumValue()
    {
        static::assertEquals('lenum', FacetType::LENUM);
        static::assertTrue(FacetType::isValid('lenum'));
    }
    /**
     * Test localized text value
     *
     * @return void
     */
    public function testLocalizedTextValue()
    {
        static::assertEquals('localized_text', FacetType::LOCALIZED_TEXT);
        static::assertTrue(FacetType::isValid('localized_text'));
    }

    /**
     * Test range value
     *
     * @return void
     */
    public function testRangeValue()
    {
        static::assertEquals('range', FacetType::RANGE);
        static::assertTrue(FacetType::isValid('range'));
    }

    /**
     * Test term value
     *
     * @return void
     */
    public function testTermValue()
    {
        static::assertEquals('terms', FacetType::TERM);
        static::assertTrue(FacetType::isValid('terms'));
    }

    /**
     * Test text value
     *
     * @return void
     */
    public function testTextValue()
    {
        static::assertEquals('text', FacetType::TEXT);
        static::assertTrue(FacetType::isValid('text'));
    }

    /**
     * Test isValid method is case sensitive
     *
     * @return void
     */
    public function testValidIsCaseSensitive()
    {
        static::assertFalse(FacetType::isValid('RANGE'));
    }

    /**
     * Test isValid method is false
     *
     * @return void
     */
    public function testValidIsFalse()
    {
        static::assertFalse(FacetType::isValid('foo'));
    }

    /**
     * Test isValid method is true
     *
     * @return void
     */
    public function testValidIsTrue()
    {
        static::assertTrue(FacetType::isValid('range'));
    }
}

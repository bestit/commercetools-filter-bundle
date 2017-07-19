<?php

namespace BestIt\Commercetools\FilterBundle\Tests\Unit\Model;

use BestIt\Commercetools\FilterBundle\Enum\FacetType;
use BestIt\Commercetools\FilterBundle\Model\FacetConfig;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 * Test facet config model
 *
 * @author     chowanski <chowanski@bestit-online.de>
 * @category   Tests\Unit
 * @package    BestIt\Commercetools\FilterBundle
 * @subpackage Model
 * @version    $id$
 */
class FacetConfigTest extends TestCase
{
    /**
     * The model to test
     *
     * @var FacetConfig
     */
    private $fixture;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->fixture = new FacetConfig();
    }

    /**
     * Test facet field fallback
     *
     * @return void
     */
    public function testGetFacetFieldFallback()
    {
        $map = [
            FacetType::TEXT => 'variants.attributes.foo',
            FacetType::RANGE => 'foo:range(0 to *)',
            FacetType::CATEGORY => 'categories.id',
            FacetType::ENUM => 'variants.attributes.foo.key',
            FacetType::LENUM => 'variants.attributes.foo.key',
            FacetType::LOCALIZED_TEXT => 'variants.attributes.de.foo'
        ];

        foreach ($map as $type => $query) {
            $fixture = new FacetConfig();
            $fixture->setField('foo');
            $fixture->setType($type);

            static::assertEquals($query, $fixture->getFacetField());
        }
    }

    /**
     * Test facet field fallback throw exception
     *
     * @return void
     */
    public function testGetFacetFieldFallbackThrowException()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->fixture->getFacetField();
    }

    /**
     * Test filter field fallback
     *
     * @return void
     */
    public function testGetFilterFieldFallback()
    {
        $map = [
            FacetType::TEXT => 'variants.attributes.foo',
            FacetType::RANGE => 'foo',
            FacetType::CATEGORY => 'categories.id',
            FacetType::ENUM => 'variants.attributes.foo.key',
            FacetType::LENUM => 'variants.attributes.foo.key',
            FacetType::LOCALIZED_TEXT => 'variants.attributes.de.foo'
        ];

        foreach ($map as $type => $query) {
            $fixture = new FacetConfig();
            $fixture->setField('foo');
            $fixture->setType($type);

            static::assertEquals($query, $fixture->getFilterField());
        }
    }

    /**
     * Test setter / getter for alias property
     *
     * @return void
     */
    public function testSetAndGetAlias()
    {
        $value = 'name';

        self::assertEquals($this->fixture, $this->fixture->setAlias($value));
        self::assertEquals($value, $this->fixture->getAlias());
    }

    /**
     * Test setter / getter for facet field property
     *
     * @return void
     */
    public function testSetAndGetFacetField()
    {
        $value = 'name';

        self::assertEquals($this->fixture, $this->fixture->setFacetField($value));
        self::assertEquals($value, $this->fixture->getFacetField());
    }

    /**
     * Test setter / getter for field property
     *
     * @return void
     */
    public function testSetAndGetField()
    {
        $value = 'name';

        self::assertEquals($this->fixture, $this->fixture->setField($value));
        self::assertEquals($value, $this->fixture->getField());
    }

    /**
     * Test setter / getter for filter field property
     *
     * @return void
     */
    public function testSetAndGetFilterField()
    {
        $value = 'name';

        self::assertEquals($this->fixture, $this->fixture->setFilterField($value));
        self::assertEquals($value, $this->fixture->getFilterField());
    }

    /**
     * Test setter / getter for hierarchical property
     *
     * @return void
     */
    public function testSetAndGetHierarchical()
    {
        $value = true;

        self::assertEquals(false, $this->fixture->isHierarchical());
        self::assertEquals($this->fixture, $this->fixture->setHierarchical($value));
        self::assertEquals($value, $this->fixture->isHierarchical());
    }

    /**
     * Test setter / getter for multi select property
     *
     * @return void
     */
    public function testSetAndGetMultiSelect()
    {
        $value = false;

        self::assertEquals(true, $this->fixture->isMultiSelect());
        self::assertEquals($this->fixture, $this->fixture->setMultiSelect($value));
        self::assertEquals($value, $this->fixture->isMultiSelect());
    }

    /**
     * Test setter / getter for name property
     *
     * @return void
     */
    public function testSetAndGetName()
    {
        $value = 'name';

        self::assertEquals($this->fixture, $this->fixture->setName($value));
        self::assertEquals($value, $this->fixture->getName());
    }

    /**
     * Test setter / getter for type property
     *
     * @return void
     */
    public function testSetAndGetType()
    {
        $value = FacetType::TEXT;

        self::assertEquals($this->fixture, $this->fixture->setType($value));
        self::assertEquals($value, $this->fixture->getType());
    }

    /**
     * Test setter / getter for weight property
     *
     * @return void
     */
    public function testSetAndGetWeight()
    {
        $value = 20;

        self::assertEquals(0, $this->fixture->getWeight());
        self::assertEquals($this->fixture, $this->fixture->setWeight($value));
        self::assertEquals($value, $this->fixture->getWeight());
    }

    /**
     * Test type setter validation
     *
     * @return void
     */
    public function testSetTypeValidation()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->fixture->setType('foo');
    }

    /**
     * Test setter / getter for weight property
     *
     * @return void
     */
    public function testSetAndIsShow()
    {
        self::assertFalse($this->fixture->isShowCount());
        self::assertEquals($this->fixture, $this->fixture->setShowCount(true));
        self::assertTrue($this->fixture->isShowCount());
    }
}

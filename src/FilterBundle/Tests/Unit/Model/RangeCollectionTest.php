<?php

namespace BestIt\Commercetools\FilterBundle\Tests\Unit\Model;

use BestIt\Commercetools\FilterBundle\Model\RangeCollection;
use PHPUnit\Framework\TestCase;

/**
 * Test range collection model
 *
 * @author     chowanski <chowanski@bestit-online.de>
 * @category   Tests\Unit
 * @package    BestIt\Commercetools\FilterBundle
 * @subpackage Model
 * @version    $id$
 */
class RangeCollectionTest extends TestCase
{
    /**
     * The model to test
     *
     * @var RangeCollection
     */
    private $fixture;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->fixture = new RangeCollection();
    }

    /**
     * Test add range
     *
     * @return void
     */
    public function testAddRange()
    {
        $this->fixture->addRange(7, 45);
        $this->fixture->addRange(6, 12);
        self::assertEquals($this->fixture, $this->fixture->addRange(4, 25));
        self::assertEquals(4, $this->fixture->getMin());
        self::assertEquals(45, $this->fixture->getMax());
    }

    /**
     * Test max getter return default value
     *
     * @return void
     */
    public function testGetMaxDefaultValue()
    {
        self::assertEquals(0, $this->fixture->getMax());
    }

    /**
     * Test min getter return default value
     *
     * @return void
     */
    public function testGetMinDefaultValue()
    {
        self::assertEquals(0, $this->fixture->getMin());
    }

    /**
     * Test setter / getter for max property
     *
     * @return void
     */
    public function testSetAndGetMax()
    {
        $value = 45;

        self::assertEquals($this->fixture, $this->fixture->setMax($value));
        self::assertEquals($value, $this->fixture->getMax());
    }

    /**
     * Test setter / getter for min property
     *
     * @return void
     */
    public function testSetAndGetMin()
    {
        $value = 45;

        self::assertEquals($this->fixture, $this->fixture->setMin($value));
        self::assertEquals($value, $this->fixture->getMin());
    }
}

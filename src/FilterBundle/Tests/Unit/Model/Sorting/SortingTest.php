<?php

namespace BestIt\Commercetools\FilterBundle\Tests\Unit\Model\Sorting;

use BestIt\Commercetools\FilterBundle\Model\Sorting\Sorting;
use PHPUnit\Framework\TestCase;

/**
 * Class SortingTest
 *
 * @author     chowanski <chowanski@bestit-online.de>
 * @category   Tests\Unit
 * @package    BestIt\Commercetools\FilterBundle
 * @subpackage Model\Sorting
 * @version    $id$
 */
class SortingTest extends TestCase
{
    /**
     * The model to test
     *
     * @var Sorting
     */
    private $fixture;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->fixture = new Sorting('name_foo', 'name.asc', 'name.de asc');
    }

    /**
     * Test constructor value
     *
     * @return void
     */
    public function testConstructor()
    {
        self::assertEquals('name_foo', $this->fixture->getKey());
        self::assertEquals('name.asc', $this->fixture->getLabel());
        self::assertEquals('name.de asc', $this->fixture->getQuery());
    }

    /**
     * Test setter / getter for active property
     *
     * @return void
     */
    public function testSetAndGetActive()
    {
        $value = true;

        self::assertEquals(false, $this->fixture->isActive());
        self::assertEquals($this->fixture, $this->fixture->setActive($value));
        self::assertEquals($value, $this->fixture->isActive());
    }

    /**
     * Test setter / getter for default property
     *
     * @return void
     */
    public function testSetAndGetDefault()
    {
        $value = true;

        self::assertEquals(false, $this->fixture->isDefault());
        self::assertEquals($this->fixture, $this->fixture->setDefault($value));
        self::assertEquals($value, $this->fixture->isDefault());
    }

    /**
     * Test setter / getter for key property
     *
     * @return void
     */
    public function testSetAndGetKey()
    {
        $value = 'name_asc';

        self::assertEquals($this->fixture, $this->fixture->setKey($value));
        self::assertEquals($value, $this->fixture->getKey());
    }

    /**
     * Test setter / getter for label property
     *
     * @return void
     */
    public function testSetAndGetLabel()
    {
        $value = 'name.asc';

        self::assertEquals($this->fixture, $this->fixture->setLabel($value));
        self::assertEquals($value, $this->fixture->getLabel());
    }

    /**
     * Test setter / getter for query property
     *
     * @return void
     */
    public function testSetAndGetQuery()
    {
        $value = 'name.de asc';

        self::assertEquals($this->fixture, $this->fixture->setQuery($value));
        self::assertEquals($value, $this->fixture->getQuery());
    }

    /**
     * Test setter / getter for query property with null
     *
     * @return void
     */
    public function testSetAndGetQueryNull()
    {
        $value = null;

        self::assertEquals($this->fixture, $this->fixture->setQuery($value));
        self::assertEquals($value, $this->fixture->getQuery());
    }
}

<?php

namespace BestIt\Commercetools\FilterBundle\Tests\Unit\Model;

use BestIt\Commercetools\FilterBundle\Model\Sorting;
use BestIt\Commercetools\FilterBundle\Model\SortingCollection;
use PHPUnit\Framework\TestCase;

/**
 * Class SortingCollectionTest
 *
 * @author     chowanski <chowanski@bestit-online.de>
 * @category   Tests\Unit
 * @package    BestIt\Commercetools\FilterBundle
 * @subpackage Model
 * @version    $id$
 */
class SortingCollectionTest extends TestCase
{
    /**
     * The model to test
     *
     * @var SortingCollection
     */
    private $fixture;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->fixture = new SortingCollection();
    }

    /**
     * Test add / getter for sorting property
     *
     * @return void
     */
    public function testAddAndGetSorting()
    {
        $value = new Sorting('name_asc', 'foo', 'bar');

        self::assertEquals($this->fixture, $this->fixture->addSorting($value));
        self::assertEquals(['name_asc' => $value], $this->fixture->all());
    }

    /**
     * Test getByKey
     *
     * @return void
     */
    public function testGetByKey()
    {
        $value = new Sorting('name_asc', 'foo', 'bar');

        self::assertEquals($this->fixture, $this->fixture->addSorting($value));
        self::assertEquals($value, $this->fixture->getByKey('name_asc'));
    }

    /**
     * Test has sorting by key
     *
     * @return void
     */
    public function testHasSorting()
    {
        $value = new Sorting('name_asc', 'foo', 'bar');

        self::assertEquals($this->fixture, $this->fixture->addSorting($value));
        self::assertEquals(true, $this->fixture->hasSorting('name_asc'));
        self::assertEquals(false, $this->fixture->hasSorting('foo'));
    }

    /**
     * Test setter / getter for active property
     *
     * @return void
     */
    public function testSetAndGetActive()
    {
        $value = new Sorting('name_asc', 'foo', 'bar');
        $this->fixture->addSorting($value);

        self::assertEquals($this->fixture, $this->fixture->setActive($value));
        self::assertEquals($value, $this->fixture->getActive());
    }

    /**
     * Test setter / getter for default property
     *
     * @return void
     */
    public function testSetAndGetDefault()
    {
        $value = new Sorting('name_asc', 'foo', 'bar');
        $this->fixture->addSorting($value);

        self::assertEquals($this->fixture, $this->fixture->setDefault($value));
        self::assertEquals($value, $this->fixture->getDefault());
    }
}

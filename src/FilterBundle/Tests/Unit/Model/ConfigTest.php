<?php

namespace BestIt\Commercetools\FilterBundle\Tests\Unit\Model;

use BestIt\Commercetools\FilterBundle\Model\Config;
use PHPUnit\Framework\TestCase;

/**
 * Class ConfigTest
 *
 * @author     chowanski <chowanski@bestit-online.de>
 * @category   Tests\Unit
 * @package    BestIt\Commercetools\FilterBundle
 * @subpackage Model
 * @version    $id$
 */
class ConfigTest extends TestCase
{
    /**
     * The model to test
     *
     * @var Config
     */
    private $fixture;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->fixture = new Config();
    }

    /**
     * Test setter / getter for defaultSorting property
     *
     * @return void
     */
    public function testSetAndGetDefaultSorting()
    {
        $value = 'name_asc';

        self::assertEquals($this->fixture, $this->fixture->setDefaultSorting($value));
        self::assertEquals($value, $this->fixture->getDefaultSorting());
    }

    /**
     * Test setter / getter for defaultView property
     *
     * @return void
     */
    public function testSetAndGetDefaultView()
    {
        $value = 'grid';

        self::assertEquals($this->fixture, $this->fixture->setDefaultView($value));
        self::assertEquals($value, $this->fixture->getDefaultView());
    }

    /**
     * Test setter / getter for itemsPerPage property
     *
     * @return void
     */
    public function testSetAndGetItemsPerPage()
    {
        $value = 3;

        self::assertEquals($this->fixture, $this->fixture->setItemsPerPage($value));
        self::assertEquals($value, $this->fixture->getItemsPerPage());
    }

    /**
     * Test setter / getter for neighbours property
     *
     * @return void
     */
    public function testSetAndGetNeighbours()
    {
        $value = 4;

        self::assertEquals($this->fixture, $this->fixture->setNeighbours($value));
        self::assertEquals($value, $this->fixture->getNeighbours());
    }

    /**
     * Test setter / getter for sortings property
     *
     * @return void
     */
    public function testSetAndGetSortings()
    {
        $value = ['name_asc' => ['key' => 'foobar']];

        self::assertEquals($this->fixture, $this->fixture->setSortings($value));
        self::assertEquals($value, $this->fixture->getSortings());
    }

    /**
     * Test setter / getter for facet property
     *
     * @return void
     */
    public function testSetAndGetFacet()
    {
        $value = ['foo' => 'bar'];

        self::assertEquals($this->fixture, $this->fixture->setFacet($value));
        self::assertEquals($value, $this->fixture->getFacet());
    }

    /**
     * Test setter / getter for translation domain property
     *
     * @return void
     */
    public function testSetAndGetTranslationDomain()
    {
        $value = 'view';

        self::assertEquals($this->fixture, $this->fixture->setTranslationDomain($value));
        self::assertEquals($value, $this->fixture->getTranslationDomain());
    }
}

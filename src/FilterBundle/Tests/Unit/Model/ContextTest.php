<?php

namespace BestIt\Commercetools\FilterBundle\Tests\Unit\Model;

use BestIt\Commercetools\FilterBundle\Model\Config;
use BestIt\Commercetools\FilterBundle\Model\Context;
use PHPUnit\Framework\TestCase;

/**
 * Class ContextTest
 * @author chowanski <chowanski@bestit-online.de>
 * @category Tests\Unit
 * @package BestIt\Commercetools\FilterBundle
 * @subpackage Model
 * @version $id$
 */
class ContextTest extends TestCase
{
    /**
     * The model to test
     * @var Context
     */
    private $fixture;

    /**
     * @inheritdoc
     */
    public function setUp()
    {
        $this->fixture = new Context();
    }

    /**
     * Test setter / getter for config property
     * @return void
     */
    public function testSetAndGetConfig()
    {
        $value = new Config();

        self::assertEquals($this->fixture, $this->fixture->setConfig($value));
        self::assertEquals($value, $this->fixture->getConfig());
    }

    /**
     * Test setter / getter for page property
     * @return void
     */
    public function testSetAndGetPage()
    {
        $value = 3;

        self::assertEquals($this->fixture, $this->fixture->setPage($value));
        self::assertEquals($value, $this->fixture->getPage());
    }

    /**
     * Test setter / getter for query property
     * @return void
     */
    public function testSetAndGetQuery()
    {
        $value = ['foo' => 'bar'];

        self::assertEquals($this->fixture, $this->fixture->setQuery($value));
        self::assertEquals($value, $this->fixture->getQuery());
    }

    /**
     * Test setter / getter for route property
     * @return void
     */
    public function testSetAndGetRoute()
    {
        $value = 'home_index';

        self::assertEquals($this->fixture, $this->fixture->setRoute($value));
        self::assertEquals($value, $this->fixture->getRoute());
    }

    /**
     * Test setter / getter for sorting property
     * @return void
     */
    public function testSetAndGetSorting()
    {
        $value = 'name_asc';

        self::assertEquals($this->fixture, $this->fixture->setSorting($value));
        self::assertEquals($value, $this->fixture->getSorting());
    }

    /**
     * Test setter / getter for view property
     * @return void
     */
    public function testSetAndGetView()
    {
        $value = 'grid';

        self::assertEquals($this->fixture, $this->fixture->setView($value));
        self::assertEquals($value, $this->fixture->getView());
    }
}

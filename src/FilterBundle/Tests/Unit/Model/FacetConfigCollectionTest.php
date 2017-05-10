<?php

namespace BestIt\Commercetools\FilterBundle\Tests\Unit\Model;

use BestIt\Commercetools\FilterBundle\Model\FacetConfig;
use BestIt\Commercetools\FilterBundle\Model\FacetConfigCollection;
use PHPUnit\Framework\TestCase;

/**
 * Test facet config collection model
 * @author chowanski <chowanski@bestit-online.de>
 * @category Tests\Unit
 * @package BestIt\Commercetools\FilterBundle
 * @subpackage Model
 * @version $id$
 */
class FacetConfigCollectionTest extends TestCase
{
    /**
     * The model to test
     * @var FacetConfigCollection
     */
    private $fixture;

    /**
     * @inheritdoc
     */
    public function setUp()
    {
        $this->fixture = new FacetConfigCollection();
        $this->fixture->add((new FacetConfig())->setAlias('first')->setName('start'));
        $this->fixture->add((new FacetConfig())->setAlias('second')->setName('middle'));
        $this->fixture->add((new FacetConfig())->setAlias('third')->setName('last'));
    }

    /**
     * Test add method
     * @return void
     */
    public function testAdd()
    {
        static::assertEquals(3, count($this->fixture->all()));
    }

    /**
     * Test get all configs
     * @return void
     */
    public function testAll()
    {
        static::assertEquals('start', $this->fixture->all()[0]->getName());
        static::assertEquals('middle', $this->fixture->all()[1]->getName());
        static::assertEquals('last', $this->fixture->all()[2]->getName());
    }

    /**
     * Test find no value by alias
     * @return void
     */
    public function testFindNoValueByAlias()
    {
        static::assertEquals(null, $this->fixture->findByAlias('foo'));
    }

    /**
     * Test find no value by name
     * @return void
     */
    public function testFindNoValueByName()
    {
        static::assertEquals(null, $this->fixture->findByAlias('foo'));
    }

    /**
     * Test find by alias
     * @return void
     */
    public function testFindValueByAlias()
    {
        static::assertEquals('middle', $this->fixture->findByAlias('second')->getName());
    }

    /**
     * Test find by name
     * @return void
     */
    public function testFindValueByName()
    {
        static::assertEquals('third', $this->fixture->findByName('last')->getAlias());
    }
}

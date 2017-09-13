<?php

namespace BestIt\Commercetools\FilterBundle\Tests\Unit\Model\Facet;

use ArrayIterator;
use BestIt\Commercetools\FilterBundle\Model\Facet\Facet;
use BestIt\Commercetools\FilterBundle\Model\Facet\FacetCollection;
use BestIt\Commercetools\FilterBundle\Model\Facet\FacetConfig;
use IteratorAggregate;
use PHPUnit\Framework\TestCase;

/**
 * Test facet collection model
 *
 * @author     chowanski <chowanski@bestit-online.de>
 * @category   Tests\Unit
 * @package    BestIt\Commercetools\FilterBundle
 * @subpackage Model\Facet
 * @version    $id$
 */
class FacetCollectionTest extends TestCase
{
    /**
     * The model to test
     *
     * @var FacetCollection
     */
    private $fixture;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->fixture = new FacetCollection();
    }

    /**
     * Test add facet
     *
     * @return void
     */
    public function testAddFacet()
    {
        $value = new Facet();

        self::assertEquals($this->fixture, $this->fixture->addFacet($value));
        self::assertEquals(1, count($this->fixture->getFacets()));
    }

    /**
     * Test get iterator
     *
     * @return void
     */
    public function testGetIterator()
    {
        self::assertInstanceOf(ArrayIterator::class, $this->fixture->getIterator());
    }

    /**
     * Test iterator implementation
     *
     * @return void
     */
    public function testImplementsIteratorInterface()
    {
        self::assertInstanceOf(IteratorAggregate::class, $this->fixture);
    }

    /**
     * Test get sorted facets
     *
     * @return void
     */
    public function testSortedFacets()
    {
        $this->fixture->addFacet((new Facet)->setConfig((new FacetConfig())->setWeight(20))->setName('Last'));
        $this->fixture->addFacet((new Facet)->setConfig((new FacetConfig())->setWeight(3))->setName('First'));
        $this->fixture->addFacet((new Facet)->setConfig((new FacetConfig())->setWeight(15))->setName('Middle'));

        self::assertEquals(3, count($this->fixture->getFacets()));
        self::assertEquals('First', $this->fixture->getFacets()[0]->getName());
        self::assertEquals('Middle', $this->fixture->getFacets()[1]->getName());
        self::assertEquals('Last', $this->fixture->getFacets()[2]->getName());
    }
}

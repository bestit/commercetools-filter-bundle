<?php

namespace BestIt\Commercetools\FilterBundle\Tests\Unit\Factory;

use BestIt\Commercetools\FilterBundle\Factory\FacetConfigCollectionFactory;
use BestIt\Commercetools\FilterBundle\Factory\FacetConfigCollectionFactoryInterface;
use BestIt\Commercetools\FilterBundle\Model\FacetConfigCollection;
use PHPUnit\Framework\TestCase;

/**
 * Test for facet config collection factory
 * @author chowanski <chowanski@bestit-online.de>
 * @category Tests\Unit
 * @package BestIt\Commercetools\FilterBundle
 * @subpackage Factory
 * @version $id$
 */
class FacetConfigCollectionFactoryTest extends TestCase
{
    /**
     * The factory
     * @var FacetConfigCollectionFactory
     */
    private $fixture;

    /**
     * @inheritdoc
     */
    public function setUp()
    {
        $this->fixture = new FacetConfigCollectionFactory();
    }

    /**
     * Test implement interface
     * @return void
     */
    public function testImplementInterface()
    {
        static::assertInstanceOf(FacetConfigCollectionFactoryInterface::class, $this->fixture);
    }

    /**
     * Test create method
     * @return void
     */
    public function testCreate()
    {
        static::assertInstanceOf(FacetConfigCollection::class, $this->fixture->create());
    }
}

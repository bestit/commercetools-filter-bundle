<?php

namespace BestIt\Commercetools\FilterBundle\Tests\Unit\Manager;

use BestIt\Commercetools\FilterBundle\Helper\FacetConfigCollectionAwareTrait;
use BestIt\Commercetools\FilterBundle\Model\FacetConfigCollection;
use PHPUnit\Framework\TestCase;

/**
 * Test facet config collection aware trait
 * @author chowanski <chowanski@bestit-online.de>
 * @category Tests\Unit
 * @package BestIt\Commercetools\FilterBundle
 * @subpackage Helper
 * @version $id$
 */
class FacetConfigCollectionAwareTraitTest extends TestCase
{
    /**
     * The trait
     * @var FacetConfigCollectionAwareTrait
     */
    private $fixture;

    /**
     * @inheritdoc
     */
    public function setUp()
    {
        $this->fixture = static::getObjectForTrait(FacetConfigCollectionAwareTrait::class);
    }

    /**
     * Test setter and getter
     * @return void
     */
    public function testSetAndGet()
    {
        static::assertEquals(null, $this->fixture->getFacetConfigCollection());

        $this->fixture->setFacetConfigCollection($collection = new FacetConfigCollection());
        static::assertEquals($collection, $this->fixture->getFacetConfigCollection());
    }
}

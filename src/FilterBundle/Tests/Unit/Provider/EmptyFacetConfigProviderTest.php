<?php

namespace BestIt\Commercetools\FilterBundle\Tests\Unit\Factory;

use BestIt\Commercetools\FilterBundle\Provider\EmptyFacetConfigProvider;
use BestIt\Commercetools\FilterBundle\Provider\FacetConfigProviderInterface;
use BestIt\Commercetools\FilterBundle\Model\FacetConfigCollection;
use PHPUnit\Framework\TestCase;

/**
 * Test for facet config collection provider
 *
 * @author     chowanski <chowanski@bestit-online.de>
 * @category   Tests\Unit
 * @package    BestIt\Commercetools\FilterBundle
 * @subpackage Factory
 * @version    $id$
 */
class EmptyFacetConfigProviderTest extends TestCase
{
    /**
     * The factory
     *
     * @var EmptyFacetConfigProvider
     */
    private $fixture;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->fixture = new EmptyFacetConfigProvider();
    }

    /**
     * Test implement interface
     *
     * @return void
     */
    public function testImplementInterface()
    {
        static::assertInstanceOf(FacetConfigProviderInterface::class, $this->fixture);
    }

    /**
     * Test create method
     *
     * @return void
     */
    public function testCreate()
    {
        static::assertInstanceOf(FacetConfigCollection::class, $this->fixture->create());
    }
}

<?php

namespace BestIt\Commercetools\FilterBundle\Tests\Unit\Factory;

use BestIt\Commercetools\FilterBundle\Factory\FacetConfigCollectionFactory;
use BestIt\Commercetools\FilterBundle\Model\FacetConfigCollection;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Translation\TranslatorInterface;

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
     * The translator
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @inheritdoc
     */
    public function setUp()
    {
        $this->fixture = new FacetConfigCollectionFactory(
            $this->translator = $this->createMock(TranslatorInterface::class)
        );
    }

    /**
     * Test create method
     * @return void
     */
    public function testCreate()
    {
        $this->translator
            ->expects(self::exactly(4))
            ->method('trans')
            ->withConsecutive(
                ['facet_manufacturer', [], 'app'],
                ['facet_wares_key_name', [], 'app'],
                ['facet_color', [], 'app'],
                ['facet_price', [], 'app']
            )
            ->willReturnOnConsecutiveCalls(
                'Hersteller',
                'WKZ - Name',
                'Farbe',
                'Preis'
            );

        static::assertInstanceOf(FacetConfigCollection::class, $this->fixture->create());
    }
}

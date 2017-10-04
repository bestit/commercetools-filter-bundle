<?php

namespace BestIt\Commercetools\FilterBundle\Tests\Unit\Factory;

use BestIt\Commercetools\FilterBundle\Enum\FacetType;
use BestIt\Commercetools\FilterBundle\Event\Facet\TermEvent;
use BestIt\Commercetools\FilterBundle\Factory\FacetCollectionFactory;
use BestIt\Commercetools\FilterBundle\FilterEvent;
use BestIt\Commercetools\FilterBundle\Model\Facet\FacetCollection;
use BestIt\Commercetools\FilterBundle\Model\Facet\FacetConfig;
use BestIt\Commercetools\FilterBundle\Model\Facet\FacetConfigCollection;
use BestIt\Commercetools\FilterBundle\Model\Term\Term;
use Commercetools\Core\Model\Product\FacetResultCollection;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Test for facet collection factory
 *
 * @author     chowanski <chowanski@bestit-online.de>
 * @category   Tests\Unit
 * @package    BestIt\Commercetools\FilterBundle
 * @subpackage Factory
 * @version    $id$
 */
class FacetCollectionFactoryTest extends TestCase
{
    /**
     * The factory
     *
     * @var FacetCollectionFactory
     */
    private $fixture;

    /**
     * The facet config collection
     *
     * @var FacetConfigCollection
     */
    private $configCollection;

    /**
     * The event dispatcher
     *
     * @var EventDispatcherInterface|PHPUnit_Framework_MockObject_MockObject
     */
    private $eventDispatcher;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->fixture = new FacetCollectionFactory(
            $this->configCollection = new FacetConfigCollection(),
            $this->eventDispatcher = $this->createMock(EventDispatcherInterface::class)
        );
    }

    /**
     * Test create method
     *
     * @return void
     */
    public function testCreate()
    {
        $this->configCollection->add(
            (new FacetConfig())
                ->setName('Preis')
                ->setField('price')
                ->setAlias('price')
                ->setType(FacetType::RANGE)
        );

        $this->configCollection->add(
            $manufacturerFacetConfig = (new FacetConfig())
                ->setName('Hersteller')
                ->setField('attribute_manufacturer_name')
                ->setAlias('attribute_manufacturer_name')
                ->setType(FacetType::TEXT)
        );

        $this->configCollection->add(
            $dummyFacetConfig = (new FacetConfig())
                ->setName('foobar')
                ->setField('foobar')
                ->setAlias('foobar')
                ->setType(FacetType::TEXT)
        );

        $this->configCollection->add(
            $enumFacetConfig = (new FacetConfig())
                ->setName('enum')
                ->setField('enum')
                ->setAlias('enum')
                ->setType(FacetType::ENUM)
        );

        $facets = [
            'attribute_manufacturer_name' => [
                'terms' => [
                    ['term' => 'Astro', 'count' => 200],
                    ['term' => 'Bestit', 'count' => 78],
                    ['term' => 'Foobar GmbH', 'count' => 4],
                ],
                'type' => FacetType::TEXT
            ],
            'foobar' => [
                'terms' => [
                    ['term' => 'Malcom', 'count' => 0],
                    ['term' => 'Mustermann', 'count' => 98],
                ],
                'type' => FacetType::TEXT
            ],
            'enum' => [
                'terms' => [
                    ['term' => 'Key1', 'count' => 0],
                    ['term' => 'Key2', 'count' => 42],
                ],
                'type' => FacetType::ENUM
            ]
        ];

        $this->eventDispatcher
            ->expects(static::exactly(7))
            ->method('dispatch')
            ->withConsecutive(
                [
                    FilterEvent::FACET_TERM_COLLECT,
                    new TermEvent($manufacturerFacetConfig, new Term(200, 'Astro', 'Astro'))
                ],
                [
                    FilterEvent::FACET_TERM_COLLECT,
                    new TermEvent($manufacturerFacetConfig, new Term(78, 'Bestit', 'Bestit'))
                ],
                [
                    FilterEvent::FACET_TERM_COLLECT,
                    new TermEvent($manufacturerFacetConfig, new Term(4, 'Foobar GmbH', 'Foobar GmbH'))
                ],
                [
                    FilterEvent::FACET_TERM_COLLECT,
                    new TermEvent($dummyFacetConfig, new Term(0, 'Malcom', 'Malcom'))
                ],
                [
                    FilterEvent::FACET_TERM_COLLECT,
                    new TermEvent($dummyFacetConfig, new Term(98, 'Mustermann', 'Mustermann'))
                ],
                [
                    FilterEvent::FACET_TERM_COLLECT,
                    new TermEvent($enumFacetConfig, new Term(0, 'Key1', 'Key1'))
                ],
                [
                    FilterEvent::FACET_TERM_COLLECT,
                    new TermEvent($enumFacetConfig, new Term(42, 'Key2', 'Key2'))
                ]
            );

        $resultCollection = $this->createMock(FacetResultCollection::class);
        $resultCollection
            ->expects(self::once())
            ->method('toArray')
            ->willReturn($facets);


        $result = $this->fixture->create($resultCollection);

        static::assertInstanceOf(FacetCollection::class, $result);
        static::assertCount(3, $result->getFacets());

        // Facet
        static::assertEquals('Hersteller', $result->getFacets()[0]->getName());
        static::assertInstanceOf(FacetConfig::class, $result->getFacets()[0]->getConfig());

        // Terms
        static::assertEquals('Bestit', $result->getFacets()[0]->getTerms()->toArray()[1]->getTitle());
        static::assertEquals(78, $result->getFacets()[0]->getTerms()->toArray()[1]->getCount());

        // Terms
        static::assertEquals('Key1', $result->getFacets()[2]->getTerms()->toArray()[0]->getTitle());
    }

    /**
     * Test create method ignore non config facets
     *
     * @return void
     */
    public function testCreateIgnoreNonConfigFacets()
    {
        $this->configCollection->add(
            (new FacetConfig())
                ->setName('Hersteller')
                ->setField('attribute_manufacturer_name')
                ->setAlias('attribute_manufacturer_name')
                ->setType(FacetType::TEXT)
        );

        $this->configCollection->add(
            (new FacetConfig())
                ->setName('foobar')
                ->setField('foobar')
                ->setAlias('foobar')
                ->setType(FacetType::TEXT)
        );

        $facets = [
            'attribute_manufacturer_name' => [
                'terms' => [
                    ['term' => 'Astro', 'count' => 200],
                    ['term' => 'Bestit', 'count' => 78],
                    ['term' => 'Foobar GmbH', 'count' => 4],
                ],
                'type' => FacetType::TEXT
            ],
            'foobar365' => [
                'terms' => [
                    ['term' => 'Malcom', 'count' => 0],
                    ['term' => 'Mustermann', 'count' => 98],
                ],
                'type' => FacetType::TEXT
            ]
        ];

        $resultCollection = $this->createMock(FacetResultCollection::class);
        $resultCollection
            ->expects(self::once())
            ->method('toArray')
            ->willReturn($facets);

        $result = $this->fixture->create($resultCollection);

        static::assertCount(1, $result->getFacets());
    }
}

<?php

namespace BestIt\Commercetools\FilterBundle\Tests\Unit\Factory;

use BestIt\Commercetools\FilterBundle\Enum\FacetType;
use BestIt\Commercetools\FilterBundle\Factory\FacetCollectionFactory;
use BestIt\Commercetools\FilterBundle\Model\FacetCollection;
use BestIt\Commercetools\FilterBundle\Model\FacetConfig;
use BestIt\Commercetools\FilterBundle\Model\FacetConfigCollection;
use Commercetools\Core\Model\Product\FacetResultCollection;
use PHPUnit\Framework\TestCase;

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
     * The collection
     *
     * @var FacetConfigCollection
     */
    private $facetConfigCollection;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->fixture = new FacetCollectionFactory(
            $this->facetConfigCollection = new FacetConfigCollection()
        );

        $this->facetConfigCollection->add(
            (new FacetConfig())
                ->setName('Preis')
                ->setField('price')
                ->setAlias('price')
                ->setType(FacetType::RANGE)
        );

        $this->facetConfigCollection->add(
            (new FacetConfig())
                ->setName('Hersteller')
                ->setField('attribute_manufacturer_name')
                ->setAlias('attribute_manufacturer_name')
                ->setType(FacetType::TEXT)
        );

        $this->facetConfigCollection->add(
            (new FacetConfig())
                ->setName('foobar')
                ->setField('foobar')
                ->setAlias('foobar')
                ->setType(FacetType::TEXT)
        );
    }

    /**
     * Test create method
     *
     * @return void
     */
    public function testCreate()
    {
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
            ]
        ];

        $resultCollection = $this->createMock(FacetResultCollection::class);
        $resultCollection
            ->expects(self::once())
            ->method('toArray')
            ->willReturn($facets);

        $result = $this->fixture->create($resultCollection);

        static::assertInstanceOf(FacetCollection::class, $result);
        static::assertEquals(2, count($result->getFacets()));

        // Facet
        static::assertEquals('Hersteller', $result->getFacets()[0]->getName());
        static::assertInstanceOf(FacetConfig::class, $result->getFacets()[0]->getConfig());

        // Terms
        static::assertEquals('Bestit', $result->getFacets()[0]->getTerms()->getTerms()[1]->getTitle());
        static::assertEquals(78, $result->getFacets()[0]->getTerms()->getTerms()[1]->getCount());
    }

    /**
     * Test create method ignore non config facets
     *
     * @return void
     */
    public function testCreateIgnoreNonConfigFacets()
    {
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

        static::assertEquals(1, count($result->getFacets()));
    }
}

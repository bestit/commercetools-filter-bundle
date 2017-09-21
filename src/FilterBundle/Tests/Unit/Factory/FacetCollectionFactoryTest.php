<?php

namespace BestIt\Commercetools\FilterBundle\Tests\Unit\Factory;

use BestIt\Commercetools\FilterBundle\Enum\FacetType;
use BestIt\Commercetools\FilterBundle\Factory\FacetCollectionFactory;
use BestIt\Commercetools\FilterBundle\Helper\EnumAttributeHelper;
use BestIt\Commercetools\FilterBundle\Model\Facet\FacetCollection;
use BestIt\Commercetools\FilterBundle\Model\Facet\FacetConfig;
use BestIt\Commercetools\FilterBundle\Model\Facet\FacetConfigCollection;
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
     * Create a test fixture with default mocks or with overridden mocks.
     *
     * @param array $mocks Array with mocks to override.
     *
     * @return FacetCollectionFactory
     */
    private function createFixture(array $mocks = []): FacetCollectionFactory
    {
        if (!array_key_exists('facetConfigCollectionMock', $mocks)
            || !$mocks['facetConfigCollectionMock'] instanceof FacetConfigCollection
        ) {
            $facetConfigCollection = new FacetConfigCollection();

            $facetConfigCollection->add(
                (new FacetConfig())
                    ->setName('Preis')
                    ->setField('price')
                    ->setAlias('price')
                    ->setType(FacetType::RANGE)
            );

            $facetConfigCollection->add(
                (new FacetConfig())
                    ->setName('Hersteller')
                    ->setField('attribute_manufacturer_name')
                    ->setAlias('attribute_manufacturer_name')
                    ->setType(FacetType::TEXT)
            );

            $facetConfigCollection->add(
                (new FacetConfig())
                    ->setName('foobar')
                    ->setField('foobar')
                    ->setAlias('foobar')
                    ->setType(FacetType::TEXT)
            );

            $facetConfigCollection->add(
                (new FacetConfig())
                    ->setName('enum')
                    ->setField('enum')
                    ->setAlias('enum')
                    ->setType(FacetType::ENUM)
            );

            $mocks['facetConfigCollectionMock'] = $facetConfigCollection;
        }

        if (!array_key_exists('enumAttributeHelperMock', $mocks)
            || !$mocks['enumAttributeHelperMock'] instanceof EnumAttributeHelper
        ) {
            $mocks['enumAttributeHelperMock'] = $this->createMock(EnumAttributeHelper::class);
        }

        return new FacetCollectionFactory(
            $mocks['facetConfigCollectionMock'],
            $mocks['enumAttributeHelperMock']
        );
    }

    /**
     * {@inheritdoc}
     *
     * @return void
     */
    public function setUp()
    {
        $this->fixture = $this->createFixture();
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
            ],
            'enum' => [
                'terms' => [
                    ['term' => 'Key1', 'count' => 0],
                    ['term' => 'Key2', 'count' => 42],
                ],
                'type' => FacetType::ENUM
            ]
        ];

        $resultCollection = $this->createMock(FacetResultCollection::class);
        $resultCollection
            ->expects(self::once())
            ->method('toArray')
            ->willReturn($facets);

        $value1 = 'abc';
        $value2 = 'xyz';

        $labels = [
            'Key1' => $value1,
            'Key2' => $value2,
        ];

        $enumAttributeHelperMock = $this->createMock(EnumAttributeHelper::class);
        $enumAttributeHelperMock
            ->method('getLabels')
            ->willReturn('enum')
            ->willReturn($labels);

        $fixture = $this->createFixture(['enumAttributeHelperMock' => $enumAttributeHelperMock]);

        $result = $fixture->create($resultCollection);

        static::assertInstanceOf(FacetCollection::class, $result);
        static::assertCount(3, $result->getFacets());

        // Facet
        static::assertEquals('Hersteller', $result->getFacets()[0]->getName());
        static::assertInstanceOf(FacetConfig::class, $result->getFacets()[0]->getConfig());

        // Terms
        static::assertEquals('Bestit', $result->getFacets()[0]->getTerms()->toArray()[1]->getTitle());
        static::assertEquals(78, $result->getFacets()[0]->getTerms()->toArray()[1]->getCount());

        // Terms
        static::assertEquals('Bestit', $result->getFacets()[0]->getTerms()->toArray()[1]->getTitle());
        static::assertEquals(78, $result->getFacets()[0]->getTerms()->toArray()[1]->getCount());

        // Terms
        static::assertEquals($value1, $result->getFacets()[2]->getTerms()->toArray()[0]->getTitle());
        static::assertEquals($value2, $result->getFacets()[2]->getTerms()->toArray()[1]->getTitle());
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

        static::assertCount(1, $result->getFacets());
    }
}

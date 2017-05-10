<?php

namespace BestIt\Commercetools\FilterBundle\Tests\Unit\Builder;

use BestIt\Commercetools\FilterBundle\Builder\FacetBuilder;
use BestIt\Commercetools\FilterBundle\Enum\FacetType;
use BestIt\Commercetools\FilterBundle\Model\FacetConfig;
use BestIt\Commercetools\FilterBundle\Model\FacetConfigCollection;
use Commercetools\Core\Request\Products\ProductProjectionSearchRequest;
use PHPUnit\Framework\TestCase;

/**
 * Test for facet builder
 * @author chowanski <chowanski@bestit-online.de>
 * @category Tests\Unit
 * @package BestIt\Commercetools\FilterBundle
 * @subpackage Builder
 * @version $id$
 */
class FacetBuilderTest extends TestCase
{
    /**
     * The builde to test
     * @var FacetBuilder
     */
    private $fixture;

    /**
     * The facet config collection
     * @var FacetConfigCollection
     */
    private $facetConfigCollection;

    /**
     * @inheritdoc
     */
    public function setUp()
    {
        $this->facetConfigCollection = new FacetConfigCollection();

        $this->facetConfigCollection->add((new FacetConfig())
            ->setName('Preis')
            ->setField('price')
            ->setAlias('price')
            ->setType(FacetType::RANGE));

        $this->facetConfigCollection->add((new FacetConfig())
            ->setName('Hersteller')
            ->setField('attribute_manufacturer_name')
            ->setAlias('attribute_manufacturer_name')
            ->setType(FacetType::TEXT));

        $this->facetConfigCollection->add((new FacetConfig())
            ->setName('foobar')
            ->setField('foobar')
            ->setAlias('foobar')
            ->setType(FacetType::TEXT));

        $this->fixture = new FacetBuilder($this->facetConfigCollection);
    }

    /**
     * Test that range facet is included
     * @return void
     */
    public function testThatRangeFacetIsIncluded()
    {
        $queryParams = [];
        $request = new ProductProjectionSearchRequest();
        $this->fixture->build($request, $queryParams);

        $result = \GuzzleHttp\Psr7\parse_query((string)$request->httpRequest()->getBody());

        static::assertContains('price:range(0 to *) as price', $result['facet']);

        static::assertContains(
            'variants.attributes.attribute_manufacturer_name as attribute_manufacturer_name',
            $result['facet']
        );

        static::assertContains('variants.attributes.foobar as foobar', $result['facet']);

        static::assertContains('price:range(0 to *)', $result['filter.facets']);
        static::assertContains('price:range(0 to *)', $result['filter']);
    }

    /**
     * Test that request appends filters
     * @return void
     */
    public function testThatRequestAppendsFilters()
    {
        $request = new ProductProjectionSearchRequest();

        $selectedValues = [
            'price' => [
                'min' => '18',
                'max' => '2599',
            ],
            'attribute_manufacturer_name' => [
                0 => 'Apple',
            ]
        ];

        $this->fixture->build($request, $selectedValues);

        $result = \GuzzleHttp\Psr7\parse_query((string)$request->httpRequest()->getBody());

        static::assertContains('variants.attributes.attribute_manufacturer_name:"Apple"', $result['facet']);

        static::assertContains(
            'variants.attributes.attribute_manufacturer_name as attribute_manufacturer_name',
            $result['facet']
        );

        static::assertContains('variants.attributes.foobar as foobar', $result['facet']);

        static::assertContains('price:range(1800 to 259900)', $result['filter']);
        static::assertContains('variants.attributes.attribute_manufacturer_name:"Apple"', $result['filter']);
    }

    /**
     * Test that values are resolved
     * @return void
     */
    public function testThatSelectedValuesAreResolved()
    {
        $values = [
            'price' => [
                'min' => 18,
                'max' => 2599,
            ],
            'attribute_manufacturer_name' => ['Apple']
        ];

        $result = $this->fixture->resolve($values);

        static::assertEquals(['Apple'], $result['attribute_manufacturer_name']);
        static::assertEquals(['min' => 18, 'max' => 2599], $result['price']);
        static::assertArrayNotHasKey('foobar', $result);
    }
}

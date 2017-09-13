<?php

namespace BestIt\Commercetools\FilterBundle\Tests\Unit\Builder;

use BestIt\Commercetools\FilterBundle\Normalizer\ProductNormalizerInterface;
use BestIt\Commercetools\FilterBundle\Builder\ResponseBuilder;
use BestIt\Commercetools\FilterBundle\Factory\FacetCollectionFactory;
use BestIt\Commercetools\FilterBundle\Factory\PaginationFactory;
use BestIt\Commercetools\FilterBundle\Form\FilterType;
use BestIt\Commercetools\FilterBundle\Model\Search\SearchContext;
use BestIt\Commercetools\FilterBundle\Model\Facet\FacetCollection;
use BestIt\Commercetools\FilterBundle\Model\Pagination\Pagination;
use Commercetools\Core\Model\Product\FacetResultCollection;
use Commercetools\Core\Model\Product\ProductProjection;
use Commercetools\Core\Model\Product\ProductProjectionCollection;
use Commercetools\Core\Response\PagedSearchResponse;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormView;

/**
 * Test for response builder
 *
 * @author     chowanski <chowanski@bestit-online.de>
 * @category   Tests\Unit
 * @package    BestIt\Commercetools\FilterBundle
 * @subpackage Builder
 * @version    $id$
 */
class ResponseBuilderTest extends TestCase
{
    /**
     * The builder to test
     *
     * @var ResponseBuilder
     */
    private $fixture;

    /**
     * The normalizer
     *
     * @var ProductNormalizerInterface
     */
    private $productNormalizer;

    /**
     * Factory for pagination
     *
     * @var PaginationFactory
     */
    private $paginationFactory;

    /**
     * Form factory
     *
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * Collection factory
     *
     * @var FacetCollectionFactory
     */
    private $facetCollectionFactory;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->fixture = new ResponseBuilder(
            $this->productNormalizer = $this->createMock(ProductNormalizerInterface::class),
            $this->paginationFactory = $this->createMock(PaginationFactory::class),
            $this->formFactory = $this->createMock(FormFactoryInterface::class),
            $this->facetCollectionFactory = $this->createMock(FacetCollectionFactory::class)
        );
    }

    /**
     * Test default build function
     *
     * @return void
     */
    public function testBuild()
    {
        $context = new SearchContext(
            [
                'query' => [
                    'filter' => [
                        'foo' => 'bar'
                    ]
                ]
            ]
        );

        $rawResponse = $this->createMock(PagedSearchResponse::class);
        $rawResponse
            ->expects(self::once())
            ->method('getTotal')
            ->willReturn(300);

        $rawResponse
            ->expects(self::once())
            ->method('getFacets')
            ->willReturn($facetResultCollection = $this->createMock(FacetResultCollection::class));

        $rawResponse
            ->expects(self::once())
            ->method('toObject')
            ->willReturn($productProjectionCollection = new ProductProjectionCollection());

        $productProjectionCollection->add($productProjection = new ProductProjection());

        $this->paginationFactory
            ->expects(self::once())
            ->method('create')
            ->with(self::equalTo($context), self::equalTo(300))
            ->willReturn($pagination = new Pagination());

        $this->facetCollectionFactory
            ->expects(self::once())
            ->method('create')
            ->with(self::equalTo($facetResultCollection))
            ->willReturn($facetCollection = new FacetCollection());

        $this->formFactory
            ->expects(self::once())
            ->method('create')
            ->with(
                self::equalTo(FilterType::class),
                self::equalTo([]),
                self::equalTo([
                    'facets' => $facetCollection,
                    'context' => $context,
                    'method' => 'GET'
                ])
            )
            ->willReturn($form = $this->createMock(Form::class));

        $form
            ->expects(self::once())
            ->method('submit')
            ->with(self::equalTo(['foo' => 'bar']));

        $form
            ->expects(self::once())
            ->method('createView')
            ->willReturn($formView = $this->createMock(FormView::class));

        $this->productNormalizer
            ->expects(self::once())
            ->method('normalize')
            ->with(self::equalTo($productProjection))
            ->willReturn($products = ['PRODUCTS']);


        $this->fixture->build($context, $rawResponse);
    }
}

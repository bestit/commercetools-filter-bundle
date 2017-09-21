<?php

namespace BestIt\Commercetools\FilterBundle\Builder;

use BestIt\Commercetools\FilterBundle\Factory\FacetCollectionFactory;
use BestIt\Commercetools\FilterBundle\Factory\PaginationFactory;
use BestIt\Commercetools\FilterBundle\Form\FilterType;
use BestIt\Commercetools\FilterBundle\Model\Search\SearchContext;
use BestIt\Commercetools\FilterBundle\Model\Search\SearchResult;
use BestIt\Commercetools\FilterBundle\Normalizer\ProductNormalizerInterface;
use BestIt\Commercetools\FilterBundle\Model\Search\SuggestResult;
use Commercetools\Core\Response\PagedSearchResponse;
use Symfony\Component\Form\FormFactoryInterface;

/**
 * Builder for parsing response
 *
 * @author     chowanski <chowanski@bestit-online.de>
 * @package    BestIt\Commercetools\FilterBundle
 * @subpackage Builder
 */
class ResponseBuilder
{
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
     * The facet collection factory
     *
     * @var FacetCollectionFactory
     */
    private $facetCollectionFactory;

    /**
     * The product normalizer
     *
     * @var ProductNormalizerInterface
     */
    private $productNormalizer;

    /**
     * ResponseManager constructor.
     *
     * @param ProductNormalizerInterface $productNormalizer
     * @param PaginationFactory $paginationFactory
     * @param FormFactoryInterface $formFactory
     * @param FacetCollectionFactory $facetCollectionFactory
     */
    public function __construct(
        ProductNormalizerInterface $productNormalizer,
        PaginationFactory $paginationFactory,
        FormFactoryInterface $formFactory,
        FacetCollectionFactory $facetCollectionFactory
    ) {
        $this
            ->setProductNormalizer($productNormalizer)
            ->setPaginationFactory($paginationFactory)
            ->setFormFactory($formFactory)
            ->setFacetCollectionFactory($facetCollectionFactory);
    }

    /**
     * Build response
     *
     * @param SearchContext $context
     * @param PagedSearchResponse $pagedSearchResponse
     *
     * @return SearchResult
     */
    public function build(SearchContext $context, PagedSearchResponse $pagedSearchResponse): SearchResult
    {
        $totalProducts = $pagedSearchResponse->getTotal();
        $facets = $pagedSearchResponse->getFacets();

        $pagination = $this->getPaginationFactory()->create($context, $totalProducts);
        $facetCollection = $this->getFacetCollectionFactory()->create($facets);

        $form = $this->getFormFactory()->create(
            FilterType::class,
            [],
            [
                'facets' => $facetCollection,
                'context' => $context,
                'method' => 'GET'
            ]
        );

        // TODO: Uncool - remove hard coded name
        $form->submit($context->getQuery()['filter'] ?? []);

        $products = [];
        foreach ($pagedSearchResponse->toObject() as $product) {
            $products[] = $this->getProductNormalizer()->normalize($product);
        }

        $result = (new SearchResult())
            ->setContext($context)
            ->setProducts($products)
            ->setTotalProducts($totalProducts)
            ->setPagination($pagination)
            ->setForm($form->createView())
            ->setHttpResponse($pagedSearchResponse);

        return $result;
    }

    /**
     * Get productNormalizer
     *
     * @return ProductNormalizerInterface
     */
    private function getProductNormalizer(): ProductNormalizerInterface
    {
        return $this->productNormalizer;
    }

    /**
     * Set productNormalizer
     *
     * @param ProductNormalizerInterface $productNormalizer
     *
     * @return ResponseBuilder
     */
    private function setProductNormalizer(ProductNormalizerInterface $productNormalizer): ResponseBuilder
    {
        $this->productNormalizer = $productNormalizer;
        return $this;
    }

    /**
     * Get facetCollectionFactory
     *
     * @return FacetCollectionFactory
     */
    private function getFacetCollectionFactory(): FacetCollectionFactory
    {
        return $this->facetCollectionFactory;
    }

    /**
     * Get formFactory
     *
     * @return FormFactoryInterface
     */
    private function getFormFactory(): FormFactoryInterface
    {
        return $this->formFactory;
    }

    /**
     * Get paginationFactory
     *
     * @return PaginationFactory
     */
    private function getPaginationFactory(): PaginationFactory
    {
        return $this->paginationFactory;
    }

    /**
     * Set facetCollectionFactory
     *
     * @param FacetCollectionFactory $facetCollectionFactory
     *
     * @return ResponseBuilder
     */
    private function setFacetCollectionFactory(FacetCollectionFactory $facetCollectionFactory): ResponseBuilder
    {
        $this->facetCollectionFactory = $facetCollectionFactory;

        return $this;
    }

    /**
     * Set formFactory
     *
     * @param FormFactoryInterface $formFactory
     *
     * @return ResponseBuilder
     */
    private function setFormFactory(FormFactoryInterface $formFactory): ResponseBuilder
    {
        $this->formFactory = $formFactory;

        return $this;
    }

    /**
     * Set paginationFactory
     *
     * @param PaginationFactory $paginationFactory
     *
     * @return ResponseBuilder
     */
    private function setPaginationFactory(PaginationFactory $paginationFactory): ResponseBuilder
    {
        $this->paginationFactory = $paginationFactory;

        return $this;
    }
}

<?php

namespace BestIt\Commercetools\FilterBundle\Builder;

use BestIt\Commercetools\FilterBundle\Form\Transformer\PriceMaxDataTransformer;
use BestIt\Commercetools\FilterBundle\Form\Transformer\PriceMinDataTransformer;
use BestIt\Commercetools\FilterBundle\Model\Facet\FacetConfig;
use BestIt\Commercetools\FilterBundle\Model\Facet\FacetConfigCollection;
use Commercetools\Core\Model\Product\Search\Facet;
use Commercetools\Core\Model\Product\Search\Filter;
use Commercetools\Core\Model\Product\Search\FilterSubtree;
use Commercetools\Core\Model\Product\Search\FilterSubtreeCollection;
use Commercetools\Core\Request\Products\ProductProjectionSearchRequest;

/**
 * Builder for attaching facet to request
 *
 * @author     chowanski <chowanski@bestit-online.de>
 * @package    BestIt\Commercetools\FilterBundle
 * @subpackage Builder
 */
class FacetBuilder
{
    /**
     * The facet config collection
     *
     * @var FacetConfigCollection
     */
    private $facetConfigCollection;

    /**
     * FacetRequestBuilder constructor
     *
     * @param FacetConfigCollection $facetConfigCollection
     */
    public function __construct(FacetConfigCollection $facetConfigCollection)
    {
        $this->facetConfigCollection = $facetConfigCollection;
    }

    /**
     * Build facets to request
     *
     * @param ProductProjectionSearchRequest $request
     * @param array $values
     *
     * @return ProductProjectionSearchRequest
     */
    public function build(ProductProjectionSearchRequest $request, array $values = []): ProductProjectionSearchRequest
    {
        $aliases = array_map(
            function (FacetConfig $facetConfig) {
                return $facetConfig->getAlias();
            },
            $this->facetConfigCollection->all()
        );

        foreach ($aliases as $facetAlias) {
            $facetConfig = $this->facetConfigCollection->findByAlias($facetAlias);
            $name = $facetConfig->getFilterField();

            $request->addFacet(Facet::ofName($facetConfig->getFacetField())->setAlias($facetConfig->getAlias()));

            // force price range to be calculate out of full result facets
            if ($facetConfig->getType() === 'range') {
                $request->addFilter(Filter::ofName(sprintf('%s:range(0 to *)', $name)));
                $request->addFilterFacets(Filter::ofName(sprintf('%s:range(0 to *)', $name)));
            }

            if (isset($values[$facetConfig->getAlias()])) {
                $facetValue = $values[$facetConfig->getAlias()];

                // provide support for range
                if ($facetConfig->getType() === 'range') {
                    if (!isset($facetValue['min'], $facetValue['max'])) {
                        continue;
                    }

                    if (strlen($facetValue['min']) === 0 || strlen($facetValue['max']) === 0) {
                        continue;
                    }

                    $request->addFilter(
                        Filter::ofName(
                            sprintf(
                                '%s:range(%s to %s)',
                                $name,
                                (new PriceMinDataTransformer())->reverseTransform($facetValue['min']),
                                (new PriceMaxDataTransformer())->reverseTransform($facetValue['max'])
                            )
                        )
                    );

                    continue;
                }

                $filter = Filter::ofName($name);

                if (!$facetConfig->isHierarchical()) {
                    $filter->setValue($facetValue);
                } else {
                    $subtree = FilterSubtreeCollection::of();
                    if (!is_array($facetValue)) {
                        $facetValue = [$facetValue];
                    }
                    foreach ($facetValue as $value) {
                        $subtree->add(FilterSubtree::ofId($value));
                    }
                    $filter->setValue($subtree);
                }

                if ($facetConfig->isMultiSelect()) {
                    $request->addFilter($filter);
                    $request->addFacet($filter);
                } else {
                    $request->addFilterQuery($filter);
                }
            }
        }

        return $request;
    }

    /**
     * Resolve facets by query params
     *
     * @param array $queryParams
     *
     * @return array
     */
    public function resolve(array $queryParams): array
    {
        $resolvedValues = [];

        foreach ($queryParams as $paramName => $params) {
            if (!$facet = $this->facetConfigCollection->findByAlias($paramName)) {
                continue;
            }

            $facetName = $facet->getAlias();
            $resolvedValues[$facetName] = $params;
        }

        return $resolvedValues;
    }
}

<?php

namespace BestIt\Commercetools\FilterBundle\Model;

use ArrayIterator;
use IteratorAggregate;

/**
 * Facet collection
 * @author chowanski <chowanski@bestit-online.de>
 * @package BestIt\Commercetools\FilterBundle
 * @subpackage Model
 */
class FacetCollection implements IteratorAggregate
{
    /**
     * The facets
     * @var Facet[]
     */
    private $facets = [];

    /**
     * Add one facet
     * @param Facet $facet
     * @return FacetCollection
     */
    public function addFacet(Facet $facet): FacetCollection
    {
        $this->facets[] = $facet;

        return $this;
    }

    /**
     * Get all facets (sorted)
     * @return Facet[]
     */
    public function getFacets(): array
    {
        $facetsSorted = $this->facets;
        usort($facetsSorted, function (Facet $a, Facet $b) {
            return $a->getConfig()->getWeight() <=> $b->getConfig()->getWeight();
        });

        return $facetsSorted;
    }

    /**
     * @inheritdoc
     */
    public function getIterator()
    {
        return new ArrayIterator($this->getFacets());
    }
}

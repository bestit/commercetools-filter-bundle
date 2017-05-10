<?php

namespace BestIt\Commercetools\FilterBundle\Factory;

use BestIt\Commercetools\FilterBundle\Helper\FacetConfigCollectionAwareTrait;
use BestIt\Commercetools\FilterBundle\Model\Facet;
use BestIt\Commercetools\FilterBundle\Model\FacetCollection;
use BestIt\Commercetools\FilterBundle\Model\FacetConfigCollection;
use BestIt\Commercetools\FilterBundle\Model\RangeCollection;
use BestIt\Commercetools\FilterBundle\Model\Term;
use BestIt\Commercetools\FilterBundle\Model\TermCollection;
use Commercetools\Core\Model\Product\FacetResultCollection;

/**
 * Factory for facets
 * @author chowanski <chowanski@bestit-online.de>
 * @package BestIt\Commercetools\FilterBundle
 * @subpackage Factory
 */
class FacetCollectionFactory
{
    use FacetConfigCollectionAwareTrait;

    /**
     * FacetCollectionFactory constructor.
     * @param FacetConfigCollection $facetConfigCollection
     */
    public function __construct(FacetConfigCollection $facetConfigCollection)
    {
        $this->setFacetConfigCollection($facetConfigCollection);
    }

    /**
     * Create facets collection
     * @param FacetResultCollection $resultCollection
     * @return FacetCollection
     */
    public function create(FacetResultCollection $resultCollection): FacetCollection
    {
        $result = $resultCollection->toArray();

        $collection = new FacetCollection();
        foreach ($this->getFacetConfigCollection()->all() as $config) {
            if (!isset($result[$config->getAlias()])) {
                continue;
            }

            $facet = $result[$config->getAlias()];

            $termsCollection = new TermCollection();
            if (isset($facet['terms'])) {
                foreach ($facet['terms'] as $term) {
                    $termsCollection->addTerm(
                        (new Term())
                            ->setTitle($term['term'] ?? null)
                            ->setCount($term['count'])
                            ->setTerm($term['term'] ?? null)
                    );
                }
            }

            $rangeCollection = new RangeCollection();
            if (isset($facet['ranges'])) {
                foreach ($facet['ranges'] as $range) {
                    $rangeCollection->addRange(intval($range['min']) ?? 0, intval($range['max']) ?? 0);
                }
            }

            $collection->addFacet((new Facet())
                ->setType($facet['type'])
                ->setName($config->getName())
                ->setConfig($config)
                ->setRanges($rangeCollection)
                ->setTerms($termsCollection));
        }

        return $collection;
    }
}

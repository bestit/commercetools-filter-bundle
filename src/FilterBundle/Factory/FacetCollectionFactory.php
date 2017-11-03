<?php

namespace BestIt\Commercetools\FilterBundle\Factory;

use BestIt\Commercetools\FilterBundle\Exception\SkipTermException;
use BestIt\Commercetools\FilterBundle\Model\Facet\Facet;
use BestIt\Commercetools\FilterBundle\Model\Facet\FacetCollection;
use BestIt\Commercetools\FilterBundle\Model\Facet\FacetConfig;
use BestIt\Commercetools\FilterBundle\Model\Facet\FacetConfigCollection;
use BestIt\Commercetools\FilterBundle\Model\Facet\RangeCollection;
use BestIt\Commercetools\FilterBundle\Model\Normalizer\TermNormalizerCollection;
use BestIt\Commercetools\FilterBundle\Model\Search\SearchContext;
use BestIt\Commercetools\FilterBundle\Model\Term\Term;
use BestIt\Commercetools\FilterBundle\Model\Term\TermCollection;
use BestIt\Commercetools\FilterBundle\Normalizer\TermNormalizerInterface;
use Commercetools\Core\Model\Product\FacetResultCollection;

/**
 * Factory for facets
 *
 * @author     chowanski <chowanski@bestit-online.de>
 * @package    BestIt\Commercetools\FilterBundle
 * @subpackage Factory
 */
class FacetCollectionFactory
{
    /**
     * The collection
     *
     * @var FacetConfigCollection
     */
    private $facetConfigCollection;

    /**
     * The normalizer collection
     *
     * @var TermNormalizerCollection
     */
    private $normalizerCollection;

    /**
     * FacetCollectionFactory constructor.
     *
     * @param FacetConfigCollection $facetConfigCollection
     * @param TermNormalizerCollection $normalizerCollection
     */
    public function __construct(
        FacetConfigCollection $facetConfigCollection,
        TermNormalizerCollection $normalizerCollection
    ) {
        $this->facetConfigCollection = $facetConfigCollection;
        $this->normalizerCollection = $normalizerCollection;
    }

    /**
     * Create facets collection
     *
     * @param FacetResultCollection $resultCollection
     * @param SearchContext $searchContext
     *
     * @return FacetCollection
     */
    public function create(FacetResultCollection $resultCollection, SearchContext $searchContext): FacetCollection
    {
        $result = $resultCollection->toArray();

        $collection = new FacetCollection();

        /** @var FacetConfig $config */
        foreach ($this->facetConfigCollection as $config) {
            if (!isset($result[$config->getAlias()])) {
                continue;
            }

            $facet = $result[$config->getAlias()];

            $termsCollection = new TermCollection();
            if (isset($facet['terms'])) {
                foreach ($facet['terms'] as $term) {
                    $termModel = new Term($term['count'], $term['term'] ?? null, $term['term'] ?? null);

                    try {
                        /** @var TermNormalizerInterface $normalizer */
                        foreach ($this->normalizerCollection as $normalizer) {
                            if ($normalizer->support($config->getType())) {
                                $termModel = $normalizer->normalize($config, $termModel, $searchContext);
                            }
                        }
                    } catch (SkipTermException $exception) {
                        continue;
                    }

                    $termsCollection->addTerm($termModel);
                }
            }

            $rangeCollection = new RangeCollection();
            if (isset($facet['ranges'])) {
                foreach ($facet['ranges'] as $range) {
                    $rangeCollection->addRange(intval($range['min']) ?? 0, intval($range['max']) ?? 0);
                }
            }

            $collection->addFacet(
                (new Facet())
                    ->setType($facet['type'])
                    ->setName($config->getName())
                    ->setConfig($config)
                    ->setRanges($rangeCollection)
                    ->setTerms($termsCollection)
            );
        }

        return $collection;
    }
}

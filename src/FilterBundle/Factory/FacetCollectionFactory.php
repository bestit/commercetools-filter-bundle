<?php

namespace BestIt\Commercetools\FilterBundle\Factory;

use BestIt\Commercetools\FilterBundle\Event\Facet\TermEvent;
use BestIt\Commercetools\FilterBundle\FilterEvent;
use BestIt\Commercetools\FilterBundle\Model\Facet\Facet;
use BestIt\Commercetools\FilterBundle\Model\Facet\FacetCollection;
use BestIt\Commercetools\FilterBundle\Model\Facet\FacetConfigCollection;
use BestIt\Commercetools\FilterBundle\Model\Facet\RangeCollection;
use BestIt\Commercetools\FilterBundle\Model\Term\Term;
use BestIt\Commercetools\FilterBundle\Model\Term\TermCollection;
use Commercetools\Core\Model\Product\FacetResultCollection;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

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
     * The event dispatcher
     *
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * FacetCollectionFactory constructor.
     *
     * @param FacetConfigCollection $facetConfigCollection
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(FacetConfigCollection $facetConfigCollection, EventDispatcherInterface $eventDispatcher)
    {
        $this->facetConfigCollection = $facetConfigCollection;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * Create facets collection
     *
     * @param FacetResultCollection $resultCollection
     *
     * @return FacetCollection
     */
    public function create(FacetResultCollection $resultCollection): FacetCollection
    {
        $result = $resultCollection->toArray();

        $collection = new FacetCollection();
        foreach ($this->facetConfigCollection as $config) {
            if (!isset($result[$config->getAlias()])) {
                continue;
            }

            $facet = $result[$config->getAlias()];

            $termsCollection = new TermCollection();
            if (isset($facet['terms'])) {
                foreach ($facet['terms'] as $term) {
                    $event = new TermEvent(
                        $config,
                        new Term($term['count'], $term['term'] ?? null, $term['term'] ?? null)
                    );

                    $this->eventDispatcher->dispatch(FilterEvent::FACET_TERM_COLLECT, $event);
                    $termsCollection->addTerm($event->getTerm());
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

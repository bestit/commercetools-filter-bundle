<?php

namespace BestIt\Commercetools\FilterBundle\Factory;

use BestIt\Commercetools\FilterBundle\Enum\FacetType;
use BestIt\Commercetools\FilterBundle\Helper\EnumAttributeHelper;
use BestIt\Commercetools\FilterBundle\Helper\FacetConfigCollectionAwareTrait;
use BestIt\Commercetools\FilterBundle\Model\Facet\Facet;
use BestIt\Commercetools\FilterBundle\Model\Facet\FacetCollection;
use BestIt\Commercetools\FilterBundle\Model\Facet\FacetConfigCollection;
use BestIt\Commercetools\FilterBundle\Model\Facet\RangeCollection;
use BestIt\Commercetools\FilterBundle\Model\Term\Term;
use BestIt\Commercetools\FilterBundle\Model\Term\TermCollection;
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
    use FacetConfigCollectionAwareTrait;

    private $enumAttributeHelper;

    /**
     * FacetCollectionFactory constructor.
     *
     * @param FacetConfigCollection $facetConfigCollection
     */
    public function __construct(
        FacetConfigCollection $facetConfigCollection,
        EnumAttributeHelper $enumAttributeHelper
    ) {
        $this
            ->setFacetConfigCollection($facetConfigCollection)
            ->setEnumAttributeHelper($enumAttributeHelper);
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
        foreach ($this->getFacetConfigCollection()->all() as $config) {
            if (!isset($result[$config->getAlias()])) {
                continue;
            }

            $facet = $result[$config->getAlias()];

            $termsCollection = new TermCollection();
            if (isset($facet['terms'])) {
                $labels = [];
                if (count($facet['terms']) > 0
                    && in_array($config->getType(), [FacetType::ENUM, FacetType::LENUM], true)) {
                    $labels = $this->getEnumAttributeHelper()->getLabels($config->getField());
                }

                foreach ($facet['terms'] as $term) {
                    if (array_key_exists($term['term'], $labels)
                        && in_array($config->getType(), [FacetType::ENUM, FacetType::LENUM], true)
                    ) {
                        $label = $labels[$term['term']];
                    } else {
                        $label = $term['term'] ?? null;
                    }

                    $termsCollection->addTerm(
                        (new Term())
                            ->setTitle($label)
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

    /**
     * Getter for EnumAttributeHelper.
     *
     * @return EnumAttributeHelper
     */
    private function getEnumAttributeHelper(): EnumAttributeHelper
    {
        return $this->enumAttributeHelper;
    }

    /**
     * Setter for EnumAttributeHelper.
     *
     * @param EnumAttributeHelper $enumAttributeHelper Used enum attribute helper.
     *
     * @return $this
     */
    private function setEnumAttributeHelper(EnumAttributeHelper $enumAttributeHelper): self
    {
        $this->enumAttributeHelper = $enumAttributeHelper;

        return $this;
    }
}

<?php

namespace BestIt\Commercetools\FilterBundle\Helper;

use BestIt\Commercetools\FilterBundle\Model\Facet\FacetConfigCollection;

/**
 * Helper aware trait for facet config collection
 *
 * @author     chowanski <chowanski@bestit-online.de>
 * @package    BestIt\Commercetools\FilterBundle
 * @subpackage Manager
 */
trait FacetConfigCollectionAwareTrait
{
    /**
     * The collection
     *
     * @var FacetConfigCollection or null
     */
    private $facetConfigCollection;

    /**
     * Get facetConfigCollection
     *
     * @return FacetConfigCollection|null
     */
    public function getFacetConfigCollection()
    {
        return $this->facetConfigCollection;
    }

    /**
     * Set facetConfigCollection
     *
     * @param FacetConfigCollection $facetConfigCollection
     *
     * @return FacetConfigCollectionAwareTrait
     */
    public function setFacetConfigCollection(FacetConfigCollection $facetConfigCollection)
    {
        $this->facetConfigCollection = $facetConfigCollection;

        return $this;
    }
}

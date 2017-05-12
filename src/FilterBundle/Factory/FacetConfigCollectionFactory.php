<?php

namespace BestIt\Commercetools\FilterBundle\Factory;

use BestIt\Commercetools\FilterBundle\Model\FacetConfigCollection;

/**
 * Factory for facets config collection
 *
 * @author     chowanski <chowanski@bestit-online.de>
 * @package    BestIt\Commercetools\FilterBundle
 * @subpackage Factory
 */
class FacetConfigCollectionFactory implements FacetConfigCollectionFactoryInterface
{
    /**
     * Create facet config collection
     *
     * @return FacetConfigCollection
     */
    public function create(): FacetConfigCollection
    {
        return new FacetConfigCollection();
    }
}

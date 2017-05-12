<?php

namespace BestIt\Commercetools\FilterBundle\Factory;

use BestIt\Commercetools\FilterBundle\Model\FacetConfigCollection;

/**
 * Factory interface for facets config collection
 *
 * @author     chowanski <chowanski@bestit-online.de>
 * @package    BestIt\Commercetools\FilterBundle
 * @subpackage Factory
 */
interface FacetConfigCollectionFactoryInterface
{
    /**
     * Create facet config collection
     *
     * @return FacetConfigCollection
     */
    public function create(): FacetConfigCollection;
}

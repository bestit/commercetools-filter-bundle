<?php

namespace BestIt\Commercetools\FilterBundle\Provider;

use BestIt\Commercetools\FilterBundle\Model\Facet\FacetConfigCollection;

/**
 * Provider for facets config collection
 *
 * @author     chowanski <chowanski@bestit-online.de>
 * @package    BestIt\Commercetools\FilterBundle
 * @subpackage Provider
 */
class EmptyFacetConfigProvider implements FacetConfigProviderInterface
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

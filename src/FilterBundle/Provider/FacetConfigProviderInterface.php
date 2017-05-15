<?php

namespace BestIt\Commercetools\FilterBundle\Provider;

use BestIt\Commercetools\FilterBundle\Model\FacetConfigCollection;

/**
 * Provider interface for facets config collection
 *
 * @author     chowanski <chowanski@bestit-online.de>
 * @package    BestIt\Commercetools\FilterBundle
 * @subpackage Provider
 */
interface FacetConfigProviderInterface
{
    /**
     * Create facet config collection
     *
     * @return FacetConfigCollection
     */
    public function create(): FacetConfigCollection;
}

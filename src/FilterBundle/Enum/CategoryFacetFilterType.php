<?php

namespace BestIt\Commercetools\FilterBundle\Enum;

/**
 * Filter types for category facets
 *
 * @author Michel Chowanski <chowanski@bestit-online.de>
 * @package BestIt\Commercetools\FilterBundle\Enum
 */
class CategoryFacetFilterType
{
    /**
     * No filtering
     *
     * @var string
     */
    const NONE = 'none';

    /**
     * Get direct child categories only
     *
     * @var string
     */
    const PARENT = 'parent';

    /**
     * Get all child and sub children categories
     *
     * @var string
     */
    const ANCESTORS = 'ancestors';
}

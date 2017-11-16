<?php

namespace BestIt\Commercetools\FilterBundle\Enum;

use BestIt\Commercetools\FilterBundle\Helper\EnumIsValidTrait;

/**
 * Enum for facet types
 *
 * @author     chowanski <chowanski@bestit-online.de>
 * @package    BestIt\Commercetools\FilterBundle
 * @subpackage Enum
 */
class FacetType
{
    use EnumIsValidTrait;

    /**
     * Facet text type
     *
     * @var string
     */
    const TEXT = 'text';

    /**
     * Facet localized text type
     *
     * @var string
     */
    const LOCALIZED_TEXT = 'ltext';

    /**
     * Facet enum type
     *
     * @var string
     */
    const ENUM = 'enum';

    /**
     * Facet enum type
     *
     * @var string
     */
    const LENUM = 'lenum';

    /**
     * Facet category type
     *
     * @var string
     */
    const CATEGORY = 'categories';

    /**
     * Facet range type
     *
     * @var string
     */
    const RANGE = 'range';

    /**
     * Facet term type
     *
     * @var string
     */
    const TERM = 'terms';
}

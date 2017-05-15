<?php

namespace BestIt\Commercetools\FilterBundle\Enum;

use ReflectionClass;

/**
 * Enum for facet types
 *
 * @author     chowanski <chowanski@bestit-online.de>
 * @package    BestIt\Commercetools\FilterBundle
 * @subpackage Enum
 */
class FacetType
{
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
    const LOCALIZED_TEXT = 'localized_text';

    /**
     * Facet enum type
     *
     * @var string
     */
    const ENUM = 'enum';

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

    /**
     * Check if the given value is a valid enum type
     *
     * @param string $value
     *
     * @return bool
     */
    public static function isValid(string $value): bool
    {
        $class = new ReflectionClass(__CLASS__);

        return in_array($value, $class->getConstants());
    }
}

<?php

namespace BestIt\Commercetools\FilterBundle\Helper;

use ReflectionClass;

/**
 * Helper aware trait for facet config collection
 *
 * @author     andre.varelmann <andre.varelmann@bestit-online.de>
 * @package    BestIt\Commercetools\FilterBundle
 * @subpackage Enum
 */
trait EnumIsValidTrait
{
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
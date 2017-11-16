<?php

namespace BestIt\Commercetools\FilterBundle\Enum;

use BestIt\Commercetools\FilterBundle\Helper\EnumIsValidTrait;

/**
 * Enum for facet types
 *
 * @author     andre.varelmann <andre.varelmann@bestit-online.de>
 * @package    BestIt\Commercetools\FilterBundle
 * @subpackage Enum
 */
class SortType
{
    use EnumIsValidTrait;

    /**
     * Sort alphabetical
     *
     * @var string
     */
    const ALPHABETICAL = 'alphabetical';

    /**
     * Sort by number of hits
     *
     * @var string
     */
    const BY_NUMBER = 'by number';
}
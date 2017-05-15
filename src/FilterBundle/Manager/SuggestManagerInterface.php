<?php

namespace BestIt\Commercetools\FilterBundle\Manager;

use BestIt\Commercetools\FilterBundle\Exception\ApiException;

/**
 * Interface for suggest requests
 *
 * @author     chowanski <chowanski@bestit-online.de>
 * @package    BestIt\Commercetools\FilterBundle
 * @subpackage Manager
 */
interface SuggestManagerInterface
{
    /**
     * Get keywords by keyword
     *
     * @param string $keyword
     * @param int $max
     *
     * @throws ApiException
     *
     * @return array
     */
    public function getKeywords(string $keyword, int $max): array;

    /**
     * Get products by keyword
     *
     * @param string $keyword
     * @param int $max
     *
     * @throws ApiException
     *
     * @return array
     */
    public function getProducts(string $keyword, int $max): array;
}

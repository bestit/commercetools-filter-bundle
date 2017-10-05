<?php

namespace BestIt\Commercetools\FilterBundle\Manager;

use BestIt\Commercetools\FilterBundle\Exception\ApiException;
use BestIt\Commercetools\FilterBundle\Model\Suggest\KeywordsResult;
use BestIt\Commercetools\FilterBundle\Model\Suggest\SuggestResult;

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
     * @param string $language
     *
     * @return KeywordsResult
     */
    public function getKeywords(string $keyword, int $max, string $language = 'de'): KeywordsResult;

    /**
     * Get products by keyword
     *
     * @param string $keyword
     * @param int $max
     * @param string $language
     *
     * @return SuggestResult
     */
    public function getProducts(string $keyword, int $max, string $language = 'de'): SuggestResult;
}

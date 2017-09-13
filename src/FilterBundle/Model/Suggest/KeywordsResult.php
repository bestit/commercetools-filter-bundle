<?php

namespace BestIt\Commercetools\FilterBundle\Model\Suggest;

use Commercetools\Core\Response\ApiResponseInterface;

/**
 * Result data for suggest keywords
 *
 * @author     chowanski <chowanski@bestit-online.de>
 * @package    BestIt\Commercetools\FilterBundle
 * @subpackage Model\Suggest
 */
class KeywordsResult
{
    /**
     * Matched keywords
     *
     * @var array
     */
    private $keywords = [];

    /**
     * The origin response from commercetools
     *
     * @var ApiResponseInterface
     */
    private $httpResponse;

    /**
     * Get products
     *
     * @return array
     */
    public function getKeywords(): array
    {
        return $this->keywords;
    }

    /**
     * Set products
     *
     * @param array $keywords
     * @return KeywordsResult
     */
    public function setKeywords(array $keywords): KeywordsResult
    {
        $this->keywords = $keywords;
        return $this;
    }

    /**
     * Get httpResponse
     *
     * @return ApiResponseInterface
     */
    public function getHttpResponse(): ApiResponseInterface
    {
        return $this->httpResponse;
    }

    /**
     * Set httpResponse
     *
     * @param ApiResponseInterface|null $httpResponse
     * @return KeywordsResult
     */
    public function setHttpResponse(ApiResponseInterface $httpResponse = null): KeywordsResult
    {
        $this->httpResponse = $httpResponse;
        return $this;
    }
}

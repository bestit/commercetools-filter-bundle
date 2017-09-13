<?php

namespace BestIt\Commercetools\FilterBundle\Model\Suggest;

use BestIt\Commercetools\FilterBundle\Model\Fuzzy\FuzzyConfig;

/**
 * Config data for suggest function
 *
 * @author     chowanski <chowanski@bestit-online.de>
 * @package    BestIt\Commercetools\FilterBundle
 * @subpackage Model\Suggest
 */
class SuggestConfig
{
    /**
     * The fuzzyConfig config
     *
     * @var FuzzyConfig
     */
    private $fuzzyConfig;

    /**
     * Match variants
     *
     * @var bool
     */
    private $matchVariants = false;

    /**
     * SuggestConfig constructor.
     */
    public function __construct()
    {
        $this->fuzzyConfig = new FuzzyConfig();
    }

    /**
     * Get fuzzyConfig
     *
     * @return FuzzyConfig
     */
    public function getFuzzyConfig(): FuzzyConfig
    {
        return $this->fuzzyConfig;
    }

    /**
     * Set fuzzyConfig
     *
     * @param FuzzyConfig $fuzzyConfig
     *
     * @return self
     */
    public function setFuzzyConfig(FuzzyConfig $fuzzyConfig): self
    {
        $this->fuzzyConfig = $fuzzyConfig;

        return $this;
    }

    /**
     * Get matchVariants
     *
     * @return bool
     */
    public function isMatchVariants(): bool
    {
        return $this->matchVariants;
    }

    /**
     * Set matchVariants
     *
     * @param bool $matchVariants
     *
     * @return SuggestConfig
     */
    public function setMatchVariants(bool $matchVariants): SuggestConfig
    {
        $this->matchVariants = $matchVariants;

        return $this;
    }
}

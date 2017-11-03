<?php

namespace BestIt\Commercetools\FilterBundle\Factory;

use BestIt\Commercetools\FilterBundle\Model\Fuzzy\FuzzyConfig;
use BestIt\Commercetools\FilterBundle\Model\Suggest\SuggestConfig;

/**
 * Factory for config data
 *
 * @author chowanski <chowanski@bestit-online.de>
 * @package BestIt\Commercetools\FilterBundle
 * @subpackage Factory
 */
class SuggestConfigFactory
{
    /**
     * The config
     *
     * @var array
     */
    private $config;

    /**
     * ContextFactory constructor.
     *
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * Create the config
     *
     * @return SuggestConfig
     */
    public function create(): SuggestConfig
    {
        $config = new SuggestConfig();
        $config->setMatchVariants($this->config['suggest']['match_variants']);
        $config->setBaseCategoryQuery($this->config['base_category_query']);

        // Fuzzy
        $fuzzyConfig = new FuzzyConfig();
        $fuzzyConfig->setIsActive($this->config['suggest']['enable_fuzzy']);
        $fuzzyConfig->setLevel($this->config['suggest']['fuzzy_level']);
        $config->setFuzzyConfig($fuzzyConfig);

        return $config;
    }
}

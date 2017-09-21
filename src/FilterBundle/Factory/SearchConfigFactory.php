<?php

namespace BestIt\Commercetools\FilterBundle\Factory;

use BestIt\Commercetools\FilterBundle\Model\Fuzzy\FuzzyConfig;
use BestIt\Commercetools\FilterBundle\Model\Search\SearchConfig;

/**
 * Factory for config data
 *
 * @author     chowanski <chowanski@bestit-online.de>
 * @package    BestIt\Commercetools\FilterBundle
 * @subpackage Factory
 */
class SearchConfigFactory
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
        $this->setConfig($config);
    }

    /**
     * Create the config
     *
     * @return SearchConfig
     */
    public function create(): SearchConfig
    {
        $config = new SearchConfig();
        $config->setItemsPerPage($this->getConfig()['pagination']['products_per_page']);
        $config->setDefaultView($this->getConfig()['view']['default']);
        $config->setDefaultSorting($this->getConfig()['sorting']['default']);
        $config->setNeighbours($this->getConfig()['pagination']['neighbours']);
        $config->setSortings($this->getConfig()['sorting']['choices']);
        $config->setFacet($this->getConfig()['facet']);
        $config->setTranslationDomain($this->getConfig()['translation_domain']);
        $config->setMatchVariants($this->getConfig()['search']['match_variants']);

        // Fuzzy
        $fuzzyConfig = new FuzzyConfig();
        $fuzzyConfig->setIsActive($this->getConfig()['search']['enable_fuzzy']);
        $fuzzyConfig->setLevel($this->getConfig()['search']['fuzzy_level']);
        $config->setFuzzyConfig($fuzzyConfig);

        return $config;
    }

    /**
     * Get config
     *
     * @return array
     */
    private function getConfig(): array
    {
        return $this->config;
    }

    /**
     * Set config
     *
     * @param array $config
     *
     * @return SearchConfigFactory
     */
    private function setConfig(array $config): SearchConfigFactory
    {
        $this->config = $config;

        return $this;
    }
}

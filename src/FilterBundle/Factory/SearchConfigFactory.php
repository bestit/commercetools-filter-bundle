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
     * SearchConfigFactory constructor.
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
     * @return SearchConfig
     */
    public function create(): SearchConfig
    {
        $config = new SearchConfig();
        $config->setItemsPerPage($this->config['pagination']['products_per_page']);
        $config->setDefaultView($this->config['view']['default']);
        $config->setDefaultSorting($this->config['sorting']['default']);
        $config->setNeighbours($this->config['pagination']['neighbours']);
        $config->setSortings($this->config['sorting']['choices']);
        $config->setFacet($this->config['facet']);
        $config->setTranslationDomain($this->config['translation_domain']);
        $config->setMatchVariants($this->config['search']['match_variants']);
        $config->setBaseCategoryQuery($this->config['base_category_query']);

        // Fuzzy
        $fuzzyConfig = new FuzzyConfig();
        $fuzzyConfig->setIsActive($this->config['search']['enable_fuzzy']);
        $fuzzyConfig->setLevel($this->config['search']['fuzzy_level']);
        $config->setFuzzyConfig($fuzzyConfig);

        return $config;
    }
}

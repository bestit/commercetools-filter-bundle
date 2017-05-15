<?php

namespace BestIt\Commercetools\FilterBundle\Factory;

use BestIt\Commercetools\FilterBundle\Model\Config;

/**
 * Factory for config data
 *
 * @author     chowanski <chowanski@bestit-online.de>
 * @package    BestIt\Commercetools\FilterBundle
 * @subpackage Factory
 */
class ConfigFactory
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
     * @return Config
     */
    public function create(): Config
    {
        return new Config(
            [
                'itemsPerPage' => $this->getConfig()['pagination']['products_per_page'],
                'defaultView' => $this->getConfig()['view']['default'],
                'defaultSorting' => $this->getConfig()['sorting']['default'],
                'neighbours' => $this->getConfig()['pagination']['neighbours'],
                'pageQueryKey' => $this->getConfig()['pagination']['query_key'],
                'sortQueryKey' => $this->getConfig()['sorting']['query_key'],
                'viewQueryKey' => $this->getConfig()['view']['query_key'],
                'sortings' => $this->getConfig()['sorting']['choices'],
                'facet' => $this->getConfig()['facet'],
                'translationDomain' => $this->getConfig()['translation_domain'],
            ]
        );
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
     * @return ConfigFactory
     */
    private function setConfig(array $config): ConfigFactory
    {
        $this->config = $config;

        return $this;
    }
}

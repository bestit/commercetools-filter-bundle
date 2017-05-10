<?php

namespace BestIt\Commercetools\FilterBundle\Factory;

use BestIt\Commercetools\FilterBundle\Model\Config;

/**
 * Factory for config data
 * @author chowanski <chowanski@bestit-online.de>
 * @package BestIt\Commercetools\FilterBundle
 * @subpackage Factory
 */
class ConfigFactory
{
    /**
     * The config
     * @var array
     */
    private $config;

    /**
     * ContextFactory constructor.
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->setConfig($config);
    }

    /**
     * Create the config
     * @return Config
     */
    public function create(): Config
    {
        $defaultSorting = null;
        foreach ($this->getConfig()['sorting'] as $key => $item) {
            if ($item['default'] === true) {
                $defaultSorting = $key;
                break;
            }
        }

        return new Config([
            'itemsPerPage' => $this->getConfig()['products_per_page'],
            'defaultView' => $this->getConfig()['default_view'],
            'defaultSorting' => $defaultSorting,
            'neighbours' => $this->getConfig()['neighbours'],
            'pageQueryKey' => $this->getConfig()['page_query_key'],
            'sortQueryKey' => $this->getConfig()['sort_query_key'],
            'viewQueryKey' => $this->getConfig()['view_query_key'],
            'sortings' => $this->getConfig()['sorting'],
        ]);
    }

    /**
     * Get config
     * @return array
     */
    private function getConfig(): array
    {
        return $this->config;
    }

    /**
     * Set config
     * @param array $config
     * @return ConfigFactory
     */
    private function setConfig(array $config): ConfigFactory
    {
        $this->config = $config;

        return $this;
    }
}

<?php

namespace BestIt\Commercetools\FilterBundle\Model;

/**
 * Collection for facet config
 *
 * @author     chowanski <chowanski@bestit-online.de>
 * @package    BestIt\Commercetools\FilterBundle
 * @subpackage Model
 */
class FacetConfigCollection
{
    /**
     * Array of configs
     *
     * @var FacetConfig[]
     */
    private $configs = [];

    /**
     * Add one config
     *
     * @param FacetConfig $config
     */
    public function add(FacetConfig $config)
    {
        $this->configs[] = $config;
    }

    /**
     * Get all configs
     *
     * @return FacetConfig[]
     */
    public function all(): array
    {
        return $this->configs;
    }

    /**
     * Find config by alias
     *
     * @param string $name
     *
     * @return FacetConfig|null
     */
    public function findByAlias(string $name)
    {
        $result = null;
        foreach ($this->configs as $config) {
            if ($config->getAlias() === $name) {
                $result = $config;
                break;
            }
        }

        return $result;
    }

    /**
     * Find config by name
     *
     * @param string $name
     *
     * @return FacetConfig|null
     */
    public function findByName(string $name)
    {
        $result = null;
        foreach ($this->configs as $config) {
            if ($config->getName() === $name) {
                $result = $config;
                break;
            }
        }

        return $result;
    }
}

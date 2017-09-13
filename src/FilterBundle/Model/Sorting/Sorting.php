<?php

namespace BestIt\Commercetools\FilterBundle\Model\Sorting;

/**
 * Data model for sorting
 *
 * @author     chowanski <chowanski@bestit-online.de>
 * @package    BestIt\Commercetools\FilterBundle
 * @subpackage Model\Sorting
 */
class Sorting
{
    /**
     * The sorting key
     *
     * @var string
     */
    private $key;

    /**
     * The sorting translation key
     *
     * @var string
     */
    private $label;

    /**
     * The query for this sorting
     *
     * @var string
     */
    private $query;

    /**
     * Is default
     *
     * @var bool
     */
    private $default = false;

    /**
     * Is active
     *
     * @var bool
     */
    private $isActive = false;

    /**
     * Sorting constructor.
     *
     * @param string $key
     * @param string $label
     * @param string $query
     */
    public function __construct(string $key, string $label, string $query)
    {
        $this
            ->setKey($key)
            ->setLabel($label)
            ->setQuery($query);
    }

    /**
     * Get key
     *
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * Get label
     *
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * Get query
     *
     * @return string
     */
    public function getQuery(): string
    {
        return $this->query;
    }

    /**
     * Get isActive
     *
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->isActive;
    }

    /**
     * Get default
     *
     * @return bool
     */
    public function isDefault(): bool
    {
        return $this->default;
    }

    /**
     * Set isActive
     *
     * @param bool $isActive
     *
     * @return Sorting
     */
    public function setActive(bool $isActive): Sorting
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Set default
     *
     * @param bool $default
     *
     * @return Sorting
     */
    public function setDefault(bool $default): Sorting
    {
        $this->default = $default;

        return $this;
    }

    /**
     * Set key
     *
     * @param string $key
     *
     * @return Sorting
     */
    public function setKey(string $key): Sorting
    {
        $this->key = $key;

        return $this;
    }

    /**
     * Set label
     *
     * @param string $label
     *
     * @return Sorting
     */
    public function setLabel(string $label): Sorting
    {
        $this->label = $label;

        return $this;
    }

    /**
     * Set query
     *
     * @param string $query
     *
     * @return Sorting
     */
    public function setQuery(string $query): Sorting
    {
        $this->query = $query;

        return $this;
    }
}

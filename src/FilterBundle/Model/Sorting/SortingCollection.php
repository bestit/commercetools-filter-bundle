<?php

namespace BestIt\Commercetools\FilterBundle\Model\Sorting;

/**
 * Collection for sortings
 *
 * @author     chowanski <chowanski@bestit-online.de>
 * @package    BestIt\Commercetools\FilterBundle
 * @subpackage Model\Sorting
 */
class SortingCollection
{
    /**
     * Array of available sortings
     *
     * @var Sorting[]
     */
    private $collection = [];

    /**
     * SortingCollection constructor.
     *
     * @param array $collection
     */
    public function __construct(array $collection = [])
    {
        foreach ($collection as $sorting) {
            $this->addSorting($sorting);
        }
    }

    /**
     * Add one sorting
     *
     * @param Sorting $sorting
     *
     * @return SortingCollection
     */
    public function addSorting(Sorting $sorting): SortingCollection
    {
        $this->collection[$sorting->getKey()] = $sorting;

        return $this;
    }

    /**
     * Get sorting
     *
     * @return Sorting[]
     */
    public function all(): array
    {
        return $this->collection;
    }

    /**
     * Get active sorting
     *
     * @return Sorting|null
     */
    public function getActive()
    {
        $result = null;

        foreach ($this->all() as $sorting) {
            if ($sorting->isActive()) {
                $result = $sorting;
                break;
            }
        }

        // Fallback, if no active state exists
        if (!$result) {
            $result = $this->getDefault();
        }

        return $result;
    }

    /**
     * Get sorting by key
     *
     * @param string $key
     *
     * @return Sorting|null
     */
    public function getByKey(string $key)
    {
        return $this->collection[$key] ?? null;
    }

    /**
     * Get default
     *
     * @return Sorting|null
     */
    public function getDefault()
    {
        $result = null;

        foreach ($this->all() as $sorting) {
            if ($sorting->isDefault()) {
                $result = $sorting;
                break;
            }
        }

        return $result;
    }

    /**
     * Check if sorting with given key exists
     *
     * @param string $key
     *
     * @return bool
     */
    public function hasSorting(string $key): bool
    {
        $result = false;

        foreach ($this->all() as $sorting) {
            if ($sorting->getKey() === $key) {
                $result = true;
                break;
            }
        }

        return $result;
    }

    /**
     * Set active
     *
     * @param Sorting $activeSorting
     *
     * @return SortingCollection
     */
    public function setActive(Sorting $activeSorting): SortingCollection
    {
        foreach ($this->all() as $sorting) {
            $sorting->setActive($sorting === $activeSorting);
        }

        return $this;
    }

    /**
     * Set default sorting
     *
     * @param Sorting $defaultSorting
     *
     * @return SortingCollection
     */
    public function setDefault(Sorting $defaultSorting): SortingCollection
    {
        foreach ($this->all() as $sorting) {
            $sorting->setDefault($sorting === $defaultSorting);
        }

        return $this;
    }
}

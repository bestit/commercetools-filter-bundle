<?php

namespace BestIt\Commercetools\FilterBundle\Model\Fuzzy;

/**
 * Fuzzy config data
 *
 * @author     chowanski <chowanski@bestit-online.de>
 * @package    BestIt\Commercetools\FilterBundle
 * @subpackage Model\Fuzzy
 */
class FuzzyConfig
{
    /**
     * Is fuzzy active
     *
     * @var boolean
     */
    private $isActive = false;

    /**
     * Fuzzy level or null if not set
     *
     * @var int|null
     */
    private $level;

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
     * Set isActive
     *
     * @param bool $isActive
     *
     * @return self
     */
    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Get level
     *
     * @return int|null
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * Set level
     *
     * @param int|null $level
     *
     * @return self
     */
    public function setLevel(int $level = null): self
    {
        $this->level = $level;

        return $this;
    }
}

<?php

namespace BestIt\Commercetools\FilterBundle\Model;

/**
 * Range collection
 *
 * @author     chowanski <chowanski@bestit-online.de>
 * @package    BestIt\Commercetools\FilterBundle
 * @subpackage Model
 */
class RangeCollection
{
    /**
     * Lowest range
     *
     * @var int
     */
    private $min;

    /**
     * Highest range
     *
     * @var int
     */
    private $max;

    /**
     * Add a range
     *
     * @param int $min
     * @param int $max
     *
     * @return RangeCollection
     */
    public function addRange(int $min, int $max): RangeCollection
    {
        if (!$this->min || $min < $this->min) {
            $this->setMin($min);
        }

        if (!$this->max || $max > $this->max) {
            $this->setMax($max);
        }

        return $this;
    }

    /**
     * Get max
     *
     * @return int
     */
    public function getMax(): int
    {
        return $this->max ?? 0;
    }

    /**
     * Get min
     *
     * @return int
     */
    public function getMin(): int
    {
        return $this->min ?? 0;
    }

    /**
     * Set max
     *
     * @param int $max
     *
     * @return RangeCollection
     */
    public function setMax(int $max): RangeCollection
    {
        $this->max = $max;

        return $this;
    }

    /**
     * Set min
     *
     * @param int $min
     *
     * @return RangeCollection
     */
    public function setMin(int $min): RangeCollection
    {
        $this->min = $min;

        return $this;
    }
}

<?php

namespace BestIt\Commercetools\FilterBundle\Model;

use BestIt\Commercetools\FilterBundle\Enum\FacetType;
use InvalidArgumentException;

/**
 * Facet object
 * @author chowanski <chowanski@bestit-online.de>
 * @package BestIt\Commercetools\FilterBundle
 * @subpackage Model
 */
class Facet
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $type;

    /**
     * @var TermCollection
     */
    private $terms;

    /**
     * @var int
     */
    private $total = 0;

    /**
     * @var FacetConfig
     */
    private $config;

    /**
     * @var RangeCollection
     */
    private $ranges;

    /**
     * Facet constructor.
     */
    public function __construct()
    {
        $this
            ->setTerms(new TermCollection())
            ->setRanges(new RangeCollection());
    }


    /**
     * Gets the facet config
     * @return FacetConfig
     */
    public function getConfig(): FacetConfig
    {
        return $this->config;
    }

    /**
     * Name for alias for this facet
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Gets the range collection
     * @return RangeCollection
     */
    public function getRanges(): RangeCollection
    {
        return $this->ranges;
    }

    /**
     * Gets the terms collection
     * @return TermCollection
     */
    public function getTerms(): TermCollection
    {
        return $this->terms;
    }

    /**
     * Gets total
     * @return int
     */
    public function getTotal(): int
    {
        return $this->total;
    }

    /**
     * Gets type
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set config
     * @param FacetConfig $config
     * @return Facet
     */
    public function setConfig(FacetConfig $config): Facet
    {
        $this->config = $config;

        return $this;
    }

    /**
     * Set name
     * @param string $name
     * @return Facet
     */
    public function setName(string $name): Facet
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Set ranges
     * @param RangeCollection $ranges
     * @return Facet
     */
    public function setRanges(RangeCollection $ranges): Facet
    {
        $this->ranges = $ranges;

        return $this;
    }

    /**
     * Set terms
     * @param TermCollection $terms
     * @return Facet
     */
    public function setTerms(TermCollection $terms): Facet
    {
        $this->terms = $terms;

        return $this;
    }

    /**
     * Set total
     * @param int $total
     * @return Facet
     */
    public function setTotal(int $total): Facet
    {
        $this->total = $total;

        return $this;
    }

    /**
     * Set type
     * @param string $type
     * @return Facet
     */
    public function setType(string $type): Facet
    {
        if (!FacetType::isValid($type)) {
            throw new InvalidArgumentException('Expect valid enum value');
        }

        $this->type = $type;

        return $this;
    }
}

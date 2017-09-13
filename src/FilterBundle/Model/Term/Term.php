<?php

namespace BestIt\Commercetools\FilterBundle\Model\Term;

/**
 * Term object
 *
 * @author     chowanski <chowanski@bestit-online.de>
 * @package    BestIt\Commercetools\FilterBundle
 * @subpackage Model\Term
 */
class Term
{
    /**
     * The term
     *
     * @var string|null
     */
    private $term;

    /**
     * Count
     *
     * @var int
     */
    private $count = 0;

    /**
     * The title
     *
     * @var string|null
     */
    private $title;

    /**
     * Matched results
     *
     * @return int
     */
    public function getCount(): int
    {
        return $this->count;
    }

    /**
     * Result key must no be displayed in frontend
     *
     * @return string|null
     */
    public function getTerm()
    {
        return $this->term;
    }

    /**
     * Display name
     *
     * @return string|null
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set count
     *
     * @param int $count
     *
     * @return Term
     */
    public function setCount(int $count = null): Term
    {
        $this->count = $count;

        return $this;
    }

    /**
     * Set term
     *
     * @param string|null $term
     *
     * @return Term
     */
    public function setTerm(string $term = null): Term
    {
        $this->term = $term;

        return $this;
    }

    /**
     * Set title
     *
     * @param string|null $title
     *
     * @return Term
     */
    public function setTitle(string $title = null): Term
    {
        $this->title = $title;

        return $this;
    }
}

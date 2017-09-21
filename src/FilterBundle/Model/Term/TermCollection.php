<?php

namespace BestIt\Commercetools\FilterBundle\Model\Term;

use ArrayIterator;
use IteratorAggregate;

/**
 * Term collection
 *
 * @author     chowanski <chowanski@bestit-online.de>
 * @package    BestIt\Commercetools\FilterBundle
 * @subpackage Model\Term
 */
class TermCollection implements IteratorAggregate
{
    /**
     * The facets
     *
     * @var Term[]
     */
    private $terms = [];

    /**
     * Add one facet
     *
     * @param Term $term
     *
     * @return TermCollection
     */
    public function addTerm(Term $term): TermCollection
    {
        $this->terms[] = $term;

        return $this;
    }

    /**
     * Get amount of terms
     *
     * @return int
     */
    public function count(): int
    {
        return count($this->terms);
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        return new ArrayIterator($this->toArray());
    }

    /**
     * Get all terms (sorted)
     *
     * @return Term[]
     */
    public function toArray(): array
    {
        $termsSorted = $this->terms;
        usort(
            $termsSorted,
            function (Term $a, Term $b) {
                return $a->getTitle() <=> $b->getTitle();
            }
        );

        return $termsSorted;
    }
}

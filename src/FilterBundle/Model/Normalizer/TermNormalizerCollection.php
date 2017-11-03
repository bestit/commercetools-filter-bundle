<?php

namespace BestIt\Commercetools\FilterBundle\Model\Normalizer;

use ArrayIterator;
use BestIt\Commercetools\FilterBundle\Normalizer\TermNormalizerInterface;
use IteratorAggregate;

/**
 * Collection of all term normalizers
 *
 * @author Michel Chowanski <chowanski@bestit-online.de>
 * @package BestIt\Commercetools\FilterBundle\Model\Normalizer
 */
class TermNormalizerCollection implements IteratorAggregate
{
    /**
     * The storage
     *
     * @var TermNormalizerInterface[]
     */
    private $storage = [];

    /**
     * Add one normalizer
     *
     * @param TermNormalizerInterface $normalizer
     *
     * @return TermNormalizerCollection
     */
    public function add(TermNormalizerInterface $normalizer): TermNormalizerCollection
    {
        $this->storage[] = $normalizer;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->all());
    }

    /**
     * Get all normalizers
     *
     * @return array
     */
    public function all(): array
    {
        return $this->storage;
    }
}

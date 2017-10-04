<?php

namespace BestIt\Commercetools\FilterBundle\Event\Facet;

use BestIt\Commercetools\FilterBundle\Model\Facet\FacetConfig;
use BestIt\Commercetools\FilterBundle\Model\Term\Term;
use Symfony\Component\EventDispatcher\Event;

/**
 * Event for manipulate terms
 *
 * @author     chowanski <chowanski@bestit-online.de>
 * @package    BestIt\Commercetools\FilterBundle
 * @subpackage Event\Facet
 */
class TermEvent extends Event
{
    /**
     * The config
     *
     * @var FacetConfig
     */
    private $config;

    /**
     * The term
     *
     * @var Term
     */
    private $term;

    /**
     * TermEvent constructor.
     *
     * @param FacetConfig $config
     * @param Term $term
     */
    public function __construct(FacetConfig $config, Term $term)
    {
        $this->config = $config;
        $this->term = $term;
    }

    /**
     * Get config
     *
     * @return FacetConfig
     */
    public function getConfig(): FacetConfig
    {
        return $this->config;
    }

    /**
     * Get term
     *
     * @return Term
     */
    public function getTerm(): Term
    {
        return $this->term;
    }
}

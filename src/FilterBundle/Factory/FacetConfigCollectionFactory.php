<?php

namespace BestIt\Commercetools\FilterBundle\Factory;

use BestIt\Commercetools\FilterBundle\Enum\FacetType;
use BestIt\Commercetools\FilterBundle\Model\FacetConfig;
use BestIt\Commercetools\FilterBundle\Model\FacetConfigCollection;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Factory for facets config collection
 * @author chowanski <chowanski@bestit-online.de>
 * @package BestIt\Commercetools\FilterBundle
 * @subpackage Factory
 */
class FacetConfigCollectionFactory
{
    /**
     * The translator
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * FacetConfigCollectionFactory constructor.
     * @param TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->setTranslator($translator);
    }

    /**
     * Create facet config collection
     * @return FacetConfigCollection
     */
    public function create(): FacetConfigCollection
    {
        $translator = $this->getTranslator();

        $collection = new FacetConfigCollection();

        $collection->add((new FacetConfig())
            ->setName($translator->trans('facet_manufacturer', [], 'app'))
            ->setAlias('manufacturer')
            ->setField('manufacturer')
            ->setType(FacetType::TEXT)
            ->setMultiSelect(true));

        $collection->add((new FacetConfig())
            ->setName($translator->trans('facet_wares_key_name', [], 'app'))
            ->setAlias('waresKeyName')
            ->setField('waresKeyName')
            ->setType(FacetType::TEXT)
            ->setMultiSelect(true));

        $collection->add((new FacetConfig())
            ->setName($translator->trans('facet_color', [], 'app'))
            ->setAlias('color')
            ->setField('color')
            ->setType(FacetType::TEXT)
            ->setMultiSelect(true));

        $collection->add((new FacetConfig())
            ->setName($translator->trans('facet_price', [], 'app'))
            ->setAlias('price')
            ->setField('variants.price.centAmount')
            ->setType(FacetType::RANGE)
            ->setMultiSelect(true));

        return $collection;
    }

    /**
     * Get translator
     * @return TranslatorInterface
     */
    private function getTranslator(): TranslatorInterface
    {
        return $this->translator;
    }

    /**
     * Set translator
     * @param TranslatorInterface $translator
     * @return FacetConfigCollectionFactory
     */
    private function setTranslator(TranslatorInterface $translator): FacetConfigCollectionFactory
    {
        $this->translator = $translator;

        return $this;
    }
}

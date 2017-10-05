<?php

namespace BestIt\Commercetools\FilterBundle\Listener\Term;

use BestIt\Commercetools\FilterBundle\Enum\FacetType;
use BestIt\Commercetools\FilterBundle\Event\Facet\TermEvent;
use BestIt\Commercetools\FilterBundle\FilterEvent;
use BestIt\Commercetools\FilterBundle\Normalizer\TermNormalizerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class CategoryListener
 *
 * @author Michel Chowanski <chowanski@bestit-online.de>
 * @package BestIt\Commercetools\FilterBundle\Listener\Term
 */
class CategoryListener implements EventSubscriberInterface
{
    /**
     * Term normalizer
     *
     * @var TermNormalizerInterface
     */
    private $normalizer;

    /**
     * CategoryListener constructor.
     *
     * @param TermNormalizerInterface $normalizer
     */
    public function __construct(TermNormalizerInterface $normalizer)
    {
        $this->normalizer = $normalizer;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents(): array
    {
        return [
            FilterEvent::FACET_TERM_COLLECT => 'onCollect'
        ];
    }

    /**
     * On collect
     *
     * @param TermEvent $event
     *
     * @return void
     */
    public function onCollect(TermEvent $event)
    {
        $config = $event->getConfig();
        $term = $event->getTerm();

        if ($event->getConfig()->getType() === FacetType::CATEGORY) {
            $this->normalizer->normalize($config, $term);
        }
    }
}
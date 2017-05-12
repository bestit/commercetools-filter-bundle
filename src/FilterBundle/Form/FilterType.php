<?php

namespace BestIt\Commercetools\FilterBundle\Form;

use BestIt\Commercetools\FilterBundle\Model\Context;
use BestIt\Commercetools\FilterBundle\Model\Facet;
use BestIt\Commercetools\FilterBundle\Model\FacetCollection;
use BestIt\Commercetools\FilterBundle\Model\Term;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Form type for filter
 *
 * @author     chowanski <chowanski@bestit-online.de>
 * @package    BestIt\Commercetools\FilterBundle
 * @subpackage Form
 */
class FilterType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /**
         * Context object
         *
         * @var Context $context
         */
        $context = $options['context'];

        /**
         * Facet collection
         *
         * @var Facet $facet
         */
        foreach ($options['facets'] as $facet) {
            switch ($facet->getType()) {
                case 'terms':
                    if ($facet->getTerms()->count() === 0) {
                        continue;
                    }

                    $choices = [];
                    foreach ($facet->getTerms() as $term) {
                        if (!$title = $term->getTitle()) {
                            continue;
                        }

                        $choices[$title] = $term;
                    }

                    $builder->add($facet->getConfig()->getAlias(), TermType::class, [
                        'multiple' => $facet->getConfig()->isMultiSelect(),
                        'expanded' => true,
                        'choices' => $choices,
                        'translation_domain' => false,
                        'label' => $facet->getName(),
                        'choice_value' => function ($term) {
                            return $term instanceof Term ? $term->getTerm() : null;
                        },
                        'choice_label' => function (Term $term) {
                            return $term instanceof Term ? $term->getTitle() : null;
                        }
                    ]);
                    break;

                case 'range':
                    $builder->add($facet->getConfig()->getAlias(), MinMaxRangeType::class, [
                        'translation_domain' => false,
                        'min' => $facet->getRanges()->getMin(),
                        'max' => $facet->getRanges()->getMax(),
                        'label' => $facet->getName(),
                    ]);
                    break;
            }
        }

        if ($reset = $context->getConfig()->getFacet()['reset']) {
            $builder->add('reset', ResetType::class, [
                'translation_domain' => $context->getConfig()->getTranslationDomain(),
                'label' => $reset
            ]);
        }

        if ($submit = $context->getConfig()->getFacet()['submit']) {
            $builder->add('submit', SubmitType::class, [
                'translation_domain' => $context->getConfig()->getTranslationDomain(),
                'label' => $submit
            ]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'required' => false,
                'csrf_protection' => false,
                'allow_extra_fields' => true,
            ]
        );

        $resolver->setRequired(['facets', 'context']);
        $resolver->setAllowedTypes('facets', [FacetCollection::class]);
        $resolver->setAllowedTypes('context', [Context::class]);
    }
}

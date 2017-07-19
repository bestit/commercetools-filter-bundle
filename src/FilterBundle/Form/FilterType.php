<?php

namespace BestIt\Commercetools\FilterBundle\Form;

use BestIt\Commercetools\FilterBundle\Model\Context;
use BestIt\Commercetools\FilterBundle\Model\Facet;
use BestIt\Commercetools\FilterBundle\Model\FacetCollection;
use BestIt\Commercetools\FilterBundle\Model\Term;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
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
     * @var string FIELDNAME_VIEW Fieldname for view parameter.
     */
    const FIELDNAME_VIEW = 'view';
    /**
     * @var string FIELDNAME_PAGE Fieldname for page parameter.
     */
    const FIELDNAME_PAGE = 'page';
    /**
     * @var string FIELDNAME_SORT Fieldname for sorting parameter.
     */
    const FIELDNAME_SORTING = 'sort';

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
                        continue 2;
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
                        'choice_label' => function (Term $term) use ($facet) {
                            $label = null;

                            if ($term instanceof Term) {
                                $label = $term->getTitle();
                            }

                            if ($facet->getConfig()->isShowCount()) {
                                $label .= ' (' . $term->getCount() . ')';
                            }

                            return $label;
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
                'label' => $reset,
                'baseUrl' => $context->getBaseUrl()
            ]);
        }

        if ($submit = $context->getConfig()->getFacet()['submit']) {
            $builder->add('submit', SubmitType::class, [
                'translation_domain' => $context->getConfig()->getTranslationDomain(),
                'label' => $submit
            ]);
        }

        // Add hidden fields for views, pagination and sorting.
        $builder->add(self::FIELDNAME_VIEW, HiddenType::class);
        $builder->add(self::FIELDNAME_PAGE, HiddenType::class);
        $builder->add(self::FIELDNAME_SORTING, HiddenType::class);
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

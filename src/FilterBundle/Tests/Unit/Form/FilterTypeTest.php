<?php

namespace BestIt\Commercetools\FilterBundle\Tests\Unit\Form;

use BestIt\Commercetools\FilterBundle\Enum\FacetType as EnumFacetType;
use BestIt\Commercetools\FilterBundle\Form\FilterType;
use BestIt\Commercetools\FilterBundle\Form\MinMaxRangeType;
use BestIt\Commercetools\FilterBundle\Form\ResetType;
use BestIt\Commercetools\FilterBundle\Form\TermType;
use BestIt\Commercetools\FilterBundle\Model\Search\SearchConfig;
use BestIt\Commercetools\FilterBundle\Model\Search\SearchContext;
use BestIt\Commercetools\FilterBundle\Model\Facet\Facet;
use BestIt\Commercetools\FilterBundle\Model\Facet\FacetCollection;
use BestIt\Commercetools\FilterBundle\Model\Facet\FacetConfig;
use BestIt\Commercetools\FilterBundle\Model\Facet\RangeCollection;
use BestIt\Commercetools\FilterBundle\Model\Term\Term;
use BestIt\Commercetools\FilterBundle\Model\Term\TermCollection;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Test the filter type
 *
 * @author     chowanski <chowanski@bestit-online.de>
 * @category   Tests\Unit
 * @package    BestIt\Commercetools\FilterBundle
 * @subpackage Form
 * @version    $id$
 */
class FilterTypeTest extends TestCase
{
    /**
     * The filter type
     *
     * @var FilterType
     */
    private $fixture;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->fixture = new FilterType();
    }

    /**
     * Test build form
     *
     * @return void
     */
    public function testBuildForm()
    {
        $facetCollection = new FacetCollection();

        // Facet with 0 terms
        $facetCollection->addFacet(
            (new Facet())
                ->setType(EnumFacetType::TERM)
                ->setConfig((new FacetConfig())->setShowCount(true))
        );

        // Facet with terms
        $textFacet = new Facet();
        $textFacet->setType(EnumFacetType::TERM);
        $textFacet->setName('Foo-Bar');
        $textFacet->setConfig(
            (new FacetConfig())
                ->setMultiSelect(true)
                ->setName('FooBar')
                ->setAlias('foo.bar')
                ->setShowCount(true)
        );

        $textFacet->setTerms(
            (new TermCollection())
                ->addTerm(new Term(0))// Term without title
                ->addTerm($cTerm = new Term(12, 'cccFooBar', 'cccFooBar'))
                ->addTerm($aTerm = new Term(45, 'aaaFooBar', 'aaaFooBar'))
                ->addTerm($bTerm = new Term(16, 'bbbFooBar', 'bbbFooBar'))
        );

        $facetCollection->addFacet($textFacet);

        // Range Facet
        $rangeFacet = new Facet();
        $rangeFacet->setType(EnumFacetType::RANGE);
        $rangeFacet->setName('Foo-Range');
        $rangeFacet->setConfig(
            (new FacetConfig())
                ->setName('FooRange')
                ->setAlias('foo.range')
        );
        $rangeFacet->setRanges(
            (new RangeCollection())->addRange(1500, 3500)
        );
        $facetCollection->addFacet($rangeFacet);

        $builder = $this->createMock(FormBuilderInterface::class);
        $builder
            ->expects(self::exactly(7))
            ->method('add')
            ->withConsecutive(
                [
                    'foo.bar',
                    TermType::class,
                    [
                        'multiple' => true,
                        'expanded' => true,
                        'choices' => ['aaaFooBar' => $aTerm, 'bbbFooBar' => $bTerm, 'cccFooBar' => $cTerm],
                        'translation_domain' => false,
                        'label' => 'Foo-Bar',
                        'choice_value' => function ($term) {
                            return $term instanceof Term ? $term->getTerm() : null;
                        },
                        'choice_label' => function (Term $term) {
                            return $term instanceof Term ? $term->getTitle() : null;
                        }
                    ]
                ],
                [
                    'foo.range',
                    MinMaxRangeType::class,
                    [
                        'translation_domain' => false,
                        'min' => 1500,
                        'max' => 3500,
                        'label' => 'Foo-Range',
                    ]
                ],
                [
                    'reset',
                    ResetType::class,
                    [
                        'translation_domain' => 'messages',
                        'label' => 'reset',
                        'baseUrl' => 'http://foo'
                    ]
                ],
                [
                    'submit',
                    SubmitType::class,
                    [
                        'translation_domain' => 'messages',
                        'label' => 'submit'
                    ]
                ],
                [
                    FilterType::FIELDNAME_VIEW,
                    HiddenType::class
                ],
                [
                    FilterType::FIELDNAME_PAGE,
                    HiddenType::class
                ],
                [
                    FilterType::FIELDNAME_SORTING,
                    HiddenType::class
                ]
            );

        $this->fixture->buildForm($builder, ['facets' => $facetCollection, 'context' => new SearchContext(
            [
                'baseUrl' => 'http://foo',
                'config' => new SearchConfig(
                    [
                        'translationDomain' => 'messages',
                        'facet' => [
                            'reset' => 'reset',
                            'submit' => 'submit'
                        ]
                    ]
                )
            ]
        )]);
    }

    /**
     * Test configure options
     *
     * @return void
     */
    public function testConfigureOptions()
    {
        $resolver = $this->createMock(OptionsResolver::class);
        $resolver
            ->expects(self::once())
            ->method('setDefaults')
            ->with(
                self::equalTo(
                    [
                        'required' => false,
                        'csrf_protection' => false,
                        'allow_extra_fields' => true,
                    ]
                )
            );

        $resolver
            ->expects(self::once())
            ->method('setRequired')
            ->with(self::equalTo(['facets', 'context']));

        $resolver
            ->expects(self::exactly(2))
            ->method('setAllowedTypes')
            ->withConsecutive(
                [
                    self::equalTo('facets'),
                    self::equalTo([FacetCollection::class])
                ],
                [
                    self::equalTo('context'),
                    self::equalTo([SearchContext::class])
                ]
            );

        $this->fixture->configureOptions($resolver);
    }
}

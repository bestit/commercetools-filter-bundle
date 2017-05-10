<?php

namespace BestIt\Commercetools\FilterBundle\Tests\Unit\Form;

use BestIt\Commercetools\FilterBundle\Enum\FacetType as EnumFacetType;
use BestIt\Commercetools\FilterBundle\Form\FacetType;
use BestIt\Commercetools\FilterBundle\Form\MinMaxRange;
use BestIt\Commercetools\FilterBundle\Model\Facet;
use BestIt\Commercetools\FilterBundle\Model\FacetCollection;
use BestIt\Commercetools\FilterBundle\Model\FacetConfig;
use BestIt\Commercetools\FilterBundle\Model\RangeCollection;
use BestIt\Commercetools\FilterBundle\Model\Term;
use BestIt\Commercetools\FilterBundle\Model\TermCollection;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Test the facet type
 * @author chowanski <chowanski@bestit-online.de>
 * @category Tests\Unit
 * @package BestIt\Commercetools\FilterBundle
 * @subpackage Form
 * @version $id$
 */
class FacetTypeTest extends TestCase
{
    /**
     * The facet type
     * @var FacetType
     */
    private $fixture;

    /**
     * @inheritdoc
     */
    public function setUp()
    {
        $this->fixture = new FacetType();
    }

    /**
     * Test build form
     * @return void
     */
    public function testBuildForm()
    {
        $facetCollection = new FacetCollection();

        // Facet with 0 terms
        $facetCollection->addFacet(
            (new Facet())
                ->setType(EnumFacetType::TERM)
                ->setConfig(new FacetConfig())
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
        );

        $textFacet->setTerms(
            (new TermCollection())
                ->addTerm((new Term))// Term without title
                ->addTerm(($cTerm = (new Term)->setTitle('cccFooBar')->setCount(12)->setTerm('cccFooBar')))
                ->addTerm(($aTerm = (new Term)->setTitle('aaaFooBar')->setCount(45)->setTerm('aaaFooBar')))
                ->addTerm(($bTerm = (new Term)->setTitle('bbbFooBar')->setCount(16)->setTerm('bbbFooBar')))
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
            ->expects(self::exactly(2))
            ->method('add')
            ->withConsecutive(
                [
                    'foo.bar',
                    ChoiceType::class,
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
                    MinMaxRange::class,
                    [
                        'translation_domain' => false,
                        'min' => 1500,
                        'max' => 3500,
                        'label' => 'Foo-Range',
                    ]
                ]
            );

        $this->fixture->buildForm($builder, ['facets' => $facetCollection]);
    }

    /**
     * Test configure options
     * @return void
     */
    public function testConfigureOptions()
    {
        $resolver = $this->createMock(OptionsResolver::class);
        $resolver
            ->expects(self::once())
            ->method('setDefaults')
            ->with(self::equalTo([
                'required' => false,
                'csrf_protection' => false,
                'allow_extra_fields' => true,
            ]));

        $resolver
            ->expects(self::once())
            ->method('setRequired')
            ->with(self::equalTo(['facets']));

        $resolver
            ->expects(self::once())
            ->method('setAllowedTypes')
            ->with(self::equalTo('facets'), self::equalTo([FacetCollection::class]));

        $this->fixture->configureOptions($resolver);
    }
}

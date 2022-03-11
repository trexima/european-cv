<?php

namespace Trexima\EuropeanCvBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Trexima\EuropeanCvBundle\Entity\Embeddable\DateRange;

/**
 * Date range widget with partial dates support.
 */
class AtomicDateRangeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('beginDay', Select2Type::class, [
            'label' => false,
            'required' => false,
            'placeholder' => 'Deň',
            'choices' => array_combine(range(1, 31), range(1, 31)),
            'attr' => [
                'data-trexima-european-cv-dynamic-collection-sort-by' => 3
            ]
        ])->add('beginMonth', Select2Type::class, [
            'label' => false,
            'required' => false,
            'placeholder' => 'Mesiac',
            'choices' => array_combine(range(1, 12), range(1, 12)),
            'attr' => [
                'data-trexima-european-cv-dynamic-collection-sort-by' => 2
            ]
        ])->add('beginYear', Select2Type::class, [
            'label' => false,
            'required' => false,
            'placeholder' => 'Rok',
            'choices' => array_reverse(array_combine(range(date('Y')-100, date('Y')), range(date('Y')-100, date('Y'))), true),
            'attr' => [
                'data-trexima-european-cv-dynamic-collection-sort-by' => 1
            ]
        ])->add('endDay', Select2Type::class, [
            'label' => false,
            'required' => false,
            'placeholder' => 'Deň',
            'choices' => array_combine(range(1, 31), range(1, 31))
        ])->add('endMonth', Select2Type::class, [
            'label' => false,
            'required' => false,
            'placeholder' => 'Mesiac',
            'choices' => array_combine(range(1, 12), range(1, 12))
        ])->add('endYear', Select2Type::class, [
            'label' => false,
            'required' => false,
            'placeholder' => 'Rok',
            'choices' => array_reverse(array_combine(range(date('Y')-100, date('Y')), range(date('Y')-100, date('Y'))), true)
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => DateRange::class,
            /**
             * Callback for empty_data is required because object
             * must be instantiate for every form element not only once!
             */
            'empty_data' => function () {
                return new DateRange();
            }
        ]);
    }
}
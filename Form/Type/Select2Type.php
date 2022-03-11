<?php

namespace Trexima\EuropeanCvBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Select2 with description in options.
 */
class Select2Type extends AbstractType
{
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        if ($options['choices_description_callback']) {
            $view->vars['choices_description'] = $options['choices_description_callback']($form->getName());
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'choices_description_callback' => null
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return ChoiceType::class;
    }
}
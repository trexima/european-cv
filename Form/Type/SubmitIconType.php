<?php

namespace Trexima\EuropeanCvBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\SubmitButtonTypeInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Simple submit button with additional options allowing inserting HTML code for icons:
 *  icon_left
 *  icon_right
 */
class SubmitIconType extends AbstractType implements SubmitButtonTypeInterface
{
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['icon_left'] = $options['icon_left'];
        $view->vars['icon_right'] = $options['icon_right'];
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'icon_left' => null,
            'icon_right' => null,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return SubmitType::class;
    }
}
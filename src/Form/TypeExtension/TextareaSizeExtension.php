<?php

namespace App\Form\TypeExtension;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormTypeExtensionInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
class TextareaSizeExtension implements FormTypeExtensionInterface
{
    public function buildForm(FormBuilderInterface $formBuilderInterface , array $options)
    {

    }

    public function buildView(FormView $formView, FormInterface $formInterface, array $options)
    {
        $formView->vars['attr']['rows'] = $options['rows'];
    }

    public function finishView(FormView $formView ,FormInterface $formInterface, array $options)
    {

    }
    public function configureOptions(OptionsResolver $optionsResolver)
    {
        $optionsResolver->setDefaults([
            'rows' => 10
        ]);
    }

    public static function getExtendedTypes(): iterable
    {
        return [TextareaType::class];
    }

    public function getExtendedType()
    {
        return TextareaType::class;
    }
} 
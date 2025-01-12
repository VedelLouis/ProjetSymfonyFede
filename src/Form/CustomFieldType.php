<?php

// src/Form/CustomFieldType.php
namespace App\Form;

use App\Entity\CustomField;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class CustomFieldType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('label', TextType::class, [
                'label' => 'Label',
                'required' => true,
                'attr' => ['placeholder' => 'Nom du champ'],
            ])
            ->add('value', TextType::class, [
                'label' => 'Valeur',
                'required' => true,
                'attr' => ['placeholder' => 'Valeur du champ'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CustomField::class,
        ]);
    }
}


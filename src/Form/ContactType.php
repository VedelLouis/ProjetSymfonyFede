<?php

namespace App\Form;

use App\Entity\Contact;
use App\Entity\Group;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints\File;

class ContactType extends AbstractType
{
    // ContactType.php
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class, [
                'label' => 'Prénom *',
                'required' => true,
            ])
            ->add('lastName', TextType::class, [
                'label' => 'Nom *',
                'required' => true,
            ])
            ->add('phoneNumber', TelType::class, [
                'label' => 'Numéro de téléphone *',
                'required' => true,
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'required' => false,
            ])
            ->add('image', FileType::class, [
                'label' => 'Image du contact',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Veuillez uploader une image valide (JPG ou PNG).',
                    ]),
                ],
            ])
            ->add('groups', EntityType::class, [
                'label' => 'Ajouter le contact dans un groupe',
                'class' => Group::class,
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true,
            ])
            ->add('customFields', CollectionType::class, [
                'entry_type' => CustomFieldType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'label' => 'Champs personnalisés',
            ])
            ->add('save', SubmitType::class, [
                'label' => $options['submit_label'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
            'submit_label' => 'Ajouter le contact',
        ]);
    }
}

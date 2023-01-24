<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ProjetType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('img', FileType::class, [
                // Pas lier à l'élement d'une base de données
                'required' => false,
                'mapped' => false,
                'help' => 'Types de fichier acceptés : png, jpg, jpeg, jp2, ou webp',
                'constraints' => [
                    new Image([
                        'maxSize' => '1M',
                        'maxSizeMessage' => 'Le fichier est trop volumineux ({{ size }} {{ suffix }}. Maximum autorisé : {{ limit }} {{ suffix }})',

                        'mimeTypes' => [
                            // 'image/* = tous les types d'image
                            'image/png',
                            'image/jpeg',
                            'image/jpg',
                            'image/jp2',
                            'image/webp',
                        ],
                        'mimeTypesMessage' => 'Merci de sélectionner une image au format {{ types }}.'
                    ])
                ]
            ])
            ->add('titre', TextType::class, [
                // 'required' => true,
                'attr' => ['maxLength' => 45]
            ])
            // Générer automatiquement donc je l'enlève : ->add('slug',)
            ->add('description', TextareaType::class, [
                'required' => false,
                'attr' => ['maxLength' => 1000]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}

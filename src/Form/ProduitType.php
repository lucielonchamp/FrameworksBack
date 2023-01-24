<?php

namespace App\Form;

use App\Entity\Produit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;

class ProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'attr' => [
                    'maxlength' => 100
                ]
            ])
            ->add('description', TextareaType::class)
            ->add('price', NumberType::class, [
                'attr' => [
                    'min' => 0,
                    'step' => 0.01
                ]
            ])
            ->add('city', TextType::class, [
                'attr' => [
                    'maxlength' => 100
                ]
            ])
            ->add('postalCode', TextType::class, [
                'attr' => [
                    'maxlength' => 5
                ]
            ])
            ->add('image', FileType::class, [
                'required' => false,
                'mapped' => false,
                'constraints' => [
                    new Image([
                        'maxSize' => '2M',
                        'maxSizeMessage' => 'Le fichier est trop volumineux (max. {{ limit }} {{ suffix }}).',
                        'mimeTypes' => [
                            'image/*'
                        ],
                        'mimeTypesMessage' => 'Le fichier doit être une image (ex.: png, jpeg, ...).'
                    ])
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
        ]);
    }
}

<?php

namespace App\Form;

use App\Entity\Genre;
use App\Entity\Media;
use App\Entity\Author;
use App\Entity\Character;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class BookType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options): void {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Le titre',
                'required' => true
            ])
            ->add('summary', TextareaType::class, [
                'label' => 'Le sommaire',
                'constraints' => [
                    new Length([
                        'max' => 100
                    ]),
                ]
            ])
            ->add('release_date', IntegerType::class, [
                'label' => 'La date de sortie',
            ])

            ->add('synopsis', TextareaType::class, [
                'label' => 'Le synopsis',
                'constraints' => [
                    new Length([
                        'min' => 50
                    ]),
                ],
                'required' => false
            ])
            ->add('picture', TextType::class, [
                'label' => "L'image",
                'required' => true
            ])
            ->add('authors', EntityType::class, [
                'class' => Author::class,
                'choice_label' => 'fullname',
                'multiple' => true,
                'required' => false
            ])
            ->add('characters', EntityType::class, [
                'class' => Character::class,
                'choice_label' => 'role',
                'multiple' => true,
                'required' => false
            ])
            ->add('genres', EntityType::class, [
                'class' => Genre::class,
                'choice_label' => 'name',
                'multiple' => true,
                'required' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void {
        $resolver->setDefaults([
            'data_class' => Media::class,
        ]);
    }
}

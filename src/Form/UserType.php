<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\Length;



class UserType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options): void {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'L\'email',
                'required' => true,
            ])
            ->add('password', PasswordType::class, [
                'label' => 'Le mot de passe',
                'required' => true,
                'constraints' => [
                    new Length([
                        'min' => 6
                    ]),
                ]
            ])
            ->add('username', TextType::class, [
                'label' => 'Le pseudonyme',
                'required' => true
            ])
            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'User' => 'ROLE_USER',
                    'CatalogManager' => 'ROLE_CATALOG_MANAGER',
                    'Admin' => 'ROLE_ADMIN',

                ],
                'multiple' => true,
                'expanded' => true
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}

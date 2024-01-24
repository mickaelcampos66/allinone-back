<?php

namespace App\Form;

use App\Entity\Actor;
use App\Entity\Character;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ActorType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options): void {
        $builder
            ->add('firstname', TextType::class, [
                'label' => 'Le prénom',
                'required' => true
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Le nom',
                'required' => true
            ])
            ->add('picture', TextType::class, [
                'label' => 'La photo de l\'acteur',
                'required' => true
            ])
            ->add('nationality', TextType::class, [
                'label' => 'La nationalité',
                'required' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void {
        $resolver->setDefaults([
            'data_class' => Actor::class,
        ]);
    }
}

<?php

namespace App\Form;

use App\Entity\Media;
use App\Entity\Author;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class AuthorType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options): void {
        $builder
            ->add('fullname', TextType::class, [
                'label' => 'Le nom complet',
                'required' => false
            ])
            ->add('alias', TextType::class, [
                'label' => 'L\'alias (Personne morale)',
                'required' => false
            ])
            ->add('position', TextType::class, [
                'label' => 'La position (statut de l\'auteur)',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void {
        $resolver->setDefaults([
            'data_class' => Author::class,
        ]);
    }
}

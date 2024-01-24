<?php

namespace App\Form;

use App\Entity\Media;
use App\Entity\Season;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class SeasonType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('season_number', IntegerType::class, [
                'label' => 'La numero de la saison',
                ])
            ->add('nb_episodes', IntegerType::class, [
                'label' => 'La nombre d\'épisodes',
                ])
            ->add('media', EntityType::class, [
                'class' => Media::class,
                'choice_label' => 'title'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Season::class,
        ]);
    }
}

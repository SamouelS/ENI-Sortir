<?php

namespace App\Form;

use App\Entity\Etat;
use App\Entity\Lieu;
use App\Entity\Campus;
use App\Entity\Sortie;
use App\Entity\Participant;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom')
            ->add('dateHeureDebut')
            ->add('duree')
            ->add('dateLimiteInscription')
            ->add('nbInscriptionsMax')
            ->add('infosSortie')
            ->add('etat', EntityType::class, [
                'choice_label' => 'libelle',
                'class'=>Etat::class
            ])
            ->add('lieu', EntityType::class, [
                'choice_label' => 'nom',
                'class'=>Lieu::class
            ])
            ->add('organisateur', EntityType::class, [
                'choice_label' => 'nom',
                'class'=>Participant::class
            ])
            ->add('campus', EntityType::class, [
                'choice_label' => 'nom',
                'class'=>Campus::class
            ])
            //->add('participant')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}

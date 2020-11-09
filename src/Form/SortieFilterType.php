<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\SortieFilter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class SortieFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('campus', EntityType::class, [                 
                'choice_label' => 'nom',                 
                'class'=>Campus::class,
                "placeholder" => "tous",      
                'required' => false,   
            ])
            ->add('like', TextType::class, [  
                'label' => 'Le nom de la sortie contient :',  
                'required' => false,             
                'attr' => [
                    'placeholder' => 'Rechercher'
                ]
            ])
            ->add('dateDebut', DateType::class, [  
                'label' => 'Entre',  
                'required' => false,             
            ])
            ->add('dateFin', DateType::class, [  
                'label' => 'et',  
                'required' => false,             
            ])
            ->add('etreOrganisateur', CheckboxType::class, [  
                'label' => 'Sorties dont je suis l\'organisateur',  
                'required' => false,             
            ])
            ->add('etreInscrit', CheckboxType::class, [  
                'label' => 'Sorties dont je suis inscrit',  
                'required' => false,             
            ])
            ->add('pasInscrit', CheckboxType::class, [  
                'label' => 'Sorties dont je ne suis pas isncrit',  
                'required' => false,             
            ])
            ->add('passer', CheckboxType::class, [  
                'label' => 'Sorties passées',  
                'required' => false,             
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SortieFilter::class,
            'csrf_protection' => false,
        ]);
    }
}

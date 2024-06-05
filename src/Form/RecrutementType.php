<?php

namespace App\Form;

use App\Entity\Recrutement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

class RecrutementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre', TextType::class, [
                'label' => 'Titre',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('lien', TextType::class, [
                'label' => 'Lien externe',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('contact', EmailType::class, [
                'label' => 'Adresse e-mail de contact',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('date',DateType::class,
            array('format' => 'dd-MM-yyyy','model_timezone'=>'Europe/Paris' , 'placeholder' => array(
                        'year' => 'Année', 'month' => 'Mois', 'day' => 'Jour')
            ,'label'  => "Date "))
             ->add('annonce', TextareaType::class, [
                'label' => 'Annonce',
                'attr' => [
                    'class' => 'form-control privatisation_annonce'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Recrutement::class,
        ]);
    }
}

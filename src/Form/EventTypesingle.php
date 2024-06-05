<?php

namespace App\Form;

use App\Entity\Event;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class EventTypesingle extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

       
            ->add('date',DateTimeType::class,
            array('format' => 'dd-MM-yyyy','model_timezone'=>'Europe/Paris' , 'placeholder' => array(
                        'year' => 'Année', 'month' => 'Mois', 'day' => 'Jour',
                        'hour' => 'Heure', 'minute' => 'Minute')
            ,'label'  => "Date et heure du début de l'evenement"))
            ->add('title', TextType::class, [
                'label' => 'Titre',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => "titre de l'evenment"
                ]
            ])
            ->add('socialmeida', TextType::class, [
                'label' => 'social meida',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Instagram ou Facebook'
                ]
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => "Description de l'evenement"
                ]
            ])
            ->add('category', null, [
                'label' => 'type de Evenement',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])

            ->add('address', TextType::class, [
                'label' => 'Adresse',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Adresse du lieu'
                ]
            ])
            ->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
                $brand = $event->getData();
                $form = $event->getForm();
              
                $options = [
                    'label' => "Image de l'evenement",
                    'constraints' => [
                        new File([
                            'maxSize' => '5M',
                            'maxSizeMessage' => 'Veuillez uploader une image inférieur à {{ limit }} Mo',
                            'mimeTypes' => [
                                'image/jpeg',
                                'image/png'
                            ],
                            'mimeTypesMessage' => 'Veuillez uploader une image au format JPG ou PNG'
                        ])
                    ],
                    'attr' => [
                        'class' => 'form-control'
                    ]
                ];

                if ($brand->getMedia() !== null) {
                    $options['required'] = false;
                }

                $form->add('file', FileType::class, $options);
            })
            ->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
                $brand = $event->getData();
                $form = $event->getForm();
              
                $options = [
                    'label' => "Image de l'evenement",
                    'constraints' => [
                        new File([
                            'maxSize' => '5M',
                            'maxSizeMessage' => 'Veuillez uploader une image inférieur à {{ limit }} Mo',
                            'mimeTypes' => [
                                'image/jpeg',
                                'image/png'
                            ],
                            'mimeTypesMessage' => 'Veuillez uploader une image au format JPG ou PNG'
                        ])
                    ],
                    'attr' => [
                        'class' => 'form-control'
                    ]
                ];

                if ($brand->getLogo() !== null) {
                    $options['required'] = false;
                }

                $form->add('filelogo', FileType::class, $options);
            })
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}

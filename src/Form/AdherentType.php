<?php

namespace App\Form;

use App\Entity\Adherent;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class AdherentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Nom de la societe'
                ]
            ])
            ->add('username', TextType::class, [
                'label' => 'username',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'username'
                ]
            ])
            ->add('passworduser', TextType::class, [
                'label' => 'mot de passe de usernanme',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'mot de passe'
                ]
            ])
            
            ->add('town', TextType::class, [
                'label' => 'Ville',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'saint denis'
                ]
            ])
            ->add('zipcode', TextType::class, [
                'label' => 'Code postal',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'ex 93000'
                ]
            ])
            ->add('tel', TextType::class, [
                'label' => 'Tel',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Tel : ex 01.....'
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => 'E-Mail',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('address', TextType::class, [
                'label' => 'Adresse',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Adresse de la societe'
                ]
            ])
            ->add('url', TextType::class, [
                'label' => 'Url du Site',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Url ex : https://www.google.com '
                ]
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Description de la Societe',
                    "maxlength"   =>  "800"
                ]
            ])
            ->add('presentation', TextareaType::class, [
                'label' => 'Presentation',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Presentation de la Societe',
                    "maxlength"   =>  "250"
                ]
            ])
            ->add('typeCompany', null, [
                'label' => 'type de Company',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            
            ->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
                $brand = $event->getData();
                $form = $event->getForm();

                $options = [
                    'label' => 'Image de Fond',
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

                if ($brand->getBackground() !== null) {
                    $options['required'] = false;
                }

                $form->add('fileback', FileType::class, $options);
            })
            ->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
                $brand = $event->getData();
                $form = $event->getForm();

                $options = [
                    'label' => 'Logo de la societe',
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

                $form->add('file', FileType::class, $options);
            })
            ->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
                $brand = $event->getData();
                $form = $event->getForm();

                $options = [
                    'label' => 'image de la societe en mobile',
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

                if ($brand->getImagemobile() !== null) {
                    $options['required'] = false;
                }

                $form->add('filemobile', FileType::class, $options);
            });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Adherent::class,
        ]);
    }
}

<?php

namespace App\Form;

use App\Entity\Privatisation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints\File;

class PrivatisationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('address', TextType::class, [
                'label' => 'Adresse',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'L\'adresse complète du local privatisable'
                ]
            ])
            ->add('capacite', IntegerType::class, [
                'label' => 'Capacité',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => "Ex : 250 (en nombre de personnes)"
                ]
            ])
            ->add('surface', TextType::class, [
                'label' => 'Surface',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => "Ex : 50m²"
                ]
            ])
            ->add('service', TextType::class, [
                'label' => 'Services',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => "Services proposés (ex : traiteur - musique)"
                ]
            ])
            ->add('annonce', TextareaType::class, [
                'label' => 'Description',
                'attr' => [
                    'class' => 'form-control privatisation_annonce',
                    'placeholder' => 'Décrivez votre local'
                ]
            ])
            ->add('adherent', null, [
                    'label' => 'Adhérent',
                    'attr' => [
                        'class' => 'form-control'
                    ]
                ]
            )
            ->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
                $brand = $event->getData();
                $form = $event->getForm();

                $options = [
                    'label' => 'Photo du local',
                    'constraints' => [
                        new File([
                            'maxSize' => '5M',
                            'maxSizeMessage' => 'Veuillez uploader une image inférieur à {{ limit }} Mo',
                            'mimeTypes' => [
                                'image/jpg',
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

                if ($brand->getPhoto() !== null) {
                    $options['required'] = false;
                }

                $form->add('file', FileType::class, $options);
            });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Privatisation::class,
        ]);
    }
}

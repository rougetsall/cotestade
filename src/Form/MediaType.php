<?php

namespace App\Form;

use App\Entity\Media;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class MediaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('legend', TextType::class, [
                'label' => 'description',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('type', ChoiceType::class, [
                'label' => 'type',
                'attr' => [
                    'class' => 'form-control'
                ],
                'choices'=>[
                    'image'=>'image',
                    'video' => 'video'
                ]
            ])
            ->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $media) {
                $brand = $media->getData();
                $form = $media->getForm();
              
                $options = [
                    'label' => "Image de l'evenement",
                    'constraints' => [
                        new File([
                            'maxSize' => '5M',
                            'maxSizeMessage' => 'Veuillez uploader une image inférieur à {{ limit }} Mo',
                            'mimeTypes' => [
                                'image/jpeg',
                                'image/png',
                                'video/mp4',
                                'video/avi',
                                'video/mov',
                                'video/mpg',
                            ],
                            'mimeTypesMessage' => 'Veuillez uploader une image au format JPG ou PNG'
                        ])
                    ],
                    'attr' => [
                        'class' => 'form-control'
                    ]
                ];

                if ($brand->getFiles() !== null) {
                    $options['required'] = false;
                }

                $form->add('file', FileType::class, $options);
            })
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Media::class,
        ]);
    }
}

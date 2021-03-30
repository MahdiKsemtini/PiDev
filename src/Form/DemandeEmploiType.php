<?php

namespace App\Form;

use App\Entity\DemandeEmploi;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class DemandeEmploiType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder


            ->add('description',TextType::class,[
                    'attr'=>[

                        'placeholder'=>'description',
                        'class'=>'prompt srch_explore',
                        'name'=>'description',
                        'id'=>'id_description',
                        'maxlength'=>'200',
                    ]


                ]

            )
         
            ->add('domaine',TextType::class,[


                    'attr'=>[
                        'required'   => true,
                        'placeholder'=>'Domaine',
                        'class'=>'prompt srch_explore',
                        'name'=>'domaine',
                        'id'=>'id_domaine',
                        'maxlength'=>'64',
                    ]

                ]

            )
            ->add('nomsociete',TextType::class,[


                    'attr'=>[
                        'required'   => true,
                        'placeholder'=>'Nom societe',
                        'class'=>'prompt srch_explore',
                        'name'=>'nom_societe',
                        'id'=>'id_nom_societe',
                        'maxlength'=>'64',
                    ]


                ]


            )
            ->add('salaire',NumberType::class,[

                    'attr'=>[

                        'placeholder'=>'Salaire',
                        'class'=>'prompt srch_explore',
                        'name'=>'salaire',
                        'id'=>'id_salaire',
                        'maxlength'=>'64',
                    ]



                ]
            )
            ->add('diplome',TextType::class, [

                    'attr'=>[

                        'placeholder'=>'Diplome',
                        'class'=>'prompt srch_explore',
                        'name'=>'diplome',
                        'id'=>'id_diplome',
                        'maxlength'=>'64',
                    ]


                ]

            )


            ->add('cv', FileType::class,[

                'constraints' => [
                    new File([
                        'maxSize' => '8000k',
                        'mimeTypes' => [
                            'application/pdf',
                            'application/x-pdf',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid PDF document',
                    ])
                ],
            ])

            ->add('Envoyer', SubmitType::class,[
                'attr' => [
                'class'=>'class="btn btn-danger"']
            ]);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => DemandeEmploi::class,
        ]);
    }

    public function getDefaultOptions(array $options)
    {
        return array(
            'csrf_protection' => false,
            // Rest of options omitted
        );
    }
}

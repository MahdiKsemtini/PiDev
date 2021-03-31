<?php

namespace App\Form;

use App\Entity\DemandeStage;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Validator\Constraints\DateTime;

class DemandeStageType extends AbstractType
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
                        'required'   => false,
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
                        'required'   => false,
                        'placeholder'=>'Nom societe',
                        'class'=>'prompt srch_explore',
                        'name'=>'nom_societe',
                        'id'=>'id_nom_societe',
                        'maxlength'=>'64',
                    ]


                ]


            )

            ->add('type',TextType::class,[

                    'attr'=>[

                        'placeholder'=>'type',
                        'class'=>'prompt srch_explore',
                        'name'=>'type',
                        'id'=>'id_type',
                        'maxlength'=>'64',
                    ]


                ]


            )
            ->add('duree',NumberType::class,[

                    'attr'=>[

                        'placeholder'=>'duree',
                        'class'=>'prompt srch_explore',
                        'name'=>'duree',
                        'id'=>'id_duree',
                        'maxlength'=>'64',
                    ]


                ]


            )
            ->add('etude',TextType::class,[

                    'attr'=>[

                        'placeholder'=>'etude',
                        'class'=>'prompt srch_explore',
                        'name'=>'etude',
                        'id'=>'id_etude',
                        'maxlength'=>'64',
                    ]


                ]

            )

            ->add('Envoyer', SubmitType::class,[
                'attr' => [
                    'class'=>'class="btn btn-danger"']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => DemandeStage::class,
        ]);
    }
}

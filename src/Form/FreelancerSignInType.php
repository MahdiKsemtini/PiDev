<?php

namespace App\Form;

use App\Entity\Freelancer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FreelancerSignInType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class,[
                'attr'=>[
                    'required'   => true,
                    'placeholder'=>'Nom',
                    'class'=>'prompt srch_explore',
                    'name'=>'Nom',
                    'id'=>'id_Nom',
                    'maxlength'=>'64',
                ]
            ])
            ->add('adresse', TextType::class,[
                'attr'=>[
                    'placeholder'=>'Adresse',
                    'class'=>'prompt srch_explore',
                    'name'=>'Adresse',
                    'id'=>'id_Adresse',
                    'maxlength'=>'64',
                ]
            ])
            ->add('email', EmailType::class,[
                'attr'=>[

                    'placeholder'=>'Email',
                    'class'=>'prompt srch_explore',
                    'name'=>'Email',
                    'id'=>'id_Email',
                    'maxlength'=>'64',
                ]
            ])
            ->add('mot_de_passe', PasswordType::class,[
                'attr'=>[

                    'placeholder'=>'Mot de pass',
                    'class'=>'prompt srch_explore',
                    'name'=>'Mot de pass',
                    'id'=>'id_Mot_de_pass',
                    'maxlength'=>'64',
                ]
            ])
            ->add('photo_de_profile', FileType::class,array('data_class' => null))
            ->add('prenom', TextType::class,[
                'attr'=>[

                    'placeholder'=>'Prenom',
                    'class'=>'prompt srch_explore',
                    'name'=>'Prenom',
                    'id'=>'id_Prenom',
                    'maxlength'=>'64',
                ]
            ])
            ->add('sexe', TextType::class,[
                'attr'=>[
                    'value'=>' ',
                    'placeholder'=>'Sexe',
                    'class'=>'prompt srch_explore',
                    'name'=>'Sexe',
                    'id'=>'id_Sexe',
                    'maxlength'=>'64',
                ]
            ])
            ->add('competences', TextType::class,[
                'attr'=>[
                    'value'=>' ',
                    'placeholder'=>'Competences',
                    'class'=>'prompt srch_explore',
                    'name'=>'Competences',
                    'id'=>'id_competences',
                    'maxlength'=>'64',
                ]
            ])
            ->add('langues', TextType::class,[
                'attr'=>[
                    'value'=>' ',
                    'placeholder'=>'Langues',
                    'class'=>'prompt srch_explore',
                    'name'=>'Langues',
                    'id'=>'id_langues',
                    'maxlength'=>'64',
                ]
            ])
            ->add('compte_facebook', TextType::class,[
                'attr'=>[
                    'placeholder'=>'Comptes Facebook',
                    'class'=>'prompt srch_explore',
                    'name'=>'comptes_facebook',
                    'id'=>'id_comptes_facebook',
                    'maxlength'=>'64',
                ]
            ])
            ->add('compte_linkedin', TextType::class,[
                'attr'=>[
                    'placeholder'=>'Comptes Linkedin',
                    'class'=>'prompt srch_explore',
                    'name'=>'comptes_linkedin',
                    'id'=>'idcomptes_linkedin',
                    'maxlength'=>'64',
                ]
            ])
            ->add('compte_twitter', TextType::class,[
                'attr'=>[
                    'placeholder'=>'Comptes Twitter',
                    'class'=>'prompt srch_explore',
                    'name'=>'comptes_twitter',
                    'id'=>'id_comptes_twitter',
                    'maxlength'=>'64',
                ]
            ])


            ->add('SignIn', SubmitType::class,[
                'attr' => ['class' => 'login-btn'],
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Freelancer::class,
        ]);
    }
}

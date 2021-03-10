<?php

namespace App\Form;

use App\Entity\Freelancer;
use App\Entity\Societe;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class SocieteSignUpType extends AbstractType
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
            ->add('mot_de_pass', PasswordType::class,[
                'attr'=>[

                    'placeholder'=>'Mot de pass',
                    'class'=>'prompt srch_explore',
                    'name'=>'Mot de pass',
                    'id'=>'id_Mot_de_pass',
                    'maxlength'=>'64',
                ]
            ])
            ->add('photo_de_profile', FileType::class,[
                'attr'=>[
                    'value'=>' ',
                    'placeholder'=>'Photo_de_profile',
                    'class'=>'prompt srch_explore',
                    'name'=>'Photo_de_profile',
                    'id'=>'id_photo_de_profile',
                ]
            ])
            ->add('status_juridique', TextType::class,[
                'attr'=>[
                    'value'=>' ',
                    'placeholder'=>'Sexe',
                    'class'=>'prompt srch_explore',
                    'name'=>'Sexe',
                    'id'=>'id_Sexe',
                    'maxlength'=>'64',
                ]
            ])
            ->add('SignUp', SubmitType::class,[
                'attr' => ['class' => 'login-btn'],
            ]);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Societe::class,
        ]);
    }
}

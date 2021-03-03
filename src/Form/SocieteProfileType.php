<?php

namespace App\Form;

use App\Entity\Societe;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SocieteProfileType extends AbstractType
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
            ->add('photo_de_profile', FileType::class,array('data_class' => null))
            ->add('status_juridique', TextType::class,[
                'attr'=>[
                    'placeholder'=>'Sexe',
                    'class'=>'prompt srch_explore',
                    'name'=>'Sexe',
                    'id'=>'id_Sexe',
                    'maxlength'=>'64',
                ]
            ])
            ->add('Save', SubmitType::class,[
                'attr' => ['class' => 'login-btn'],
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Societe::class,
        ]);
    }
}

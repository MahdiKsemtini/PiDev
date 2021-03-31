<?php

namespace App\Form;

use App\Entity\Freelancer;
use phpDocumentor\Reflection\Type;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class FreelancerSignUpType extends AbstractType
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
//                    'id'=>'id_Nom',
                    'maxlength'=>'64',
                ]
            ])

            ->add('email', EmailType::class,[
                'attr'=>[

                    'placeholder'=>'Email',
                    'class'=>'prompt srch_explore',
                    'name'=>'Email',
//                    'id'=>'id_Email',
                    'maxlength'=>'64',
                ]
            ])
            ->add('mot_de_passe', PasswordType::class,[
                'attr'=>[

                    'placeholder'=>'Mot de pass',
                    'class'=>'prompt srch_explore',
                    'name'=>'Mot de pass',
//                    'id'=>'id_Mot_de_pass',
                    'maxlength'=>'64',
                ]
            ])

            ->add('prenom', TextType::class,[
                'attr'=>[

                    'placeholder'=>'Prenom',
                    'class'=>'prompt srch_explore',
                    'name'=>'Prenom',
//                    'id'=>'id_Prenom',
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
            'data_class' => Freelancer::class,
        ]);
    }
}

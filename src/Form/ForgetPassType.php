<?php

namespace App\Form;

use App\Entity\Freelancer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ForgetPassType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class,[
                'attr'=>[

                    'placeholder'=>'Email',
                    'class'=>'prompt srch_explore',
                    'name'=>'Email',
                    'id'=>'id_Email',
                    'maxlength'=>'64',
                ]
            ])
            ->add('Envoyer', SubmitType::class,[
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

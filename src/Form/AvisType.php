<?php

namespace App\Form;

use App\Entity\Avis;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AvisType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('textAvis', TextType::class,[
                'attr'=>[
                    'required'   => true,
                    'placeholder'=>'entrer votre avis',
                    'class'=>'prompt srch_explore',
                    'name'=>'textAvis',
                    'id'=>'id_textAvis',
                    'maxlength'=>'64',
                ]
            ])
            ->add('Submit', SubmitType::class,[
                'attr' => ['class' => 'login-btn'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Avis::class,
        ]);
    }
}

<?php

namespace App\Form;

use App\Entity\Quiz;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QuizType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom_quiz',TextType::class,[
                'attr'=>[
                    'required'   => true,
                    'placeholder'=>'Nom de Quiz',
                    'class'=>'prompt srch_explore',
                    'name'=>'Quiz',
                    'id'=>'Quiz',
                    'maxlength'=>'64',
                ]
            ])
            ->add('nomb_question',TextType::class,[
                'attr'=>[
                    'required'   => true,
                    'placeholder'=>'Nombre des Questions',
                    'class'=>'prompt srch_explore',
                    'name'=>'Questions',
                    'id'=>'Questions',
                    'maxlength'=>'64',
                ]
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Quiz::class,
        ]);
    }
}
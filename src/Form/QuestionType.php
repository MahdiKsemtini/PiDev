<?php

namespace App\Form;

use App\Entity\Question;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QuestionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('contenu_ques',TextType::class,[
                'attr'=>[
                    'required'   => true,
                    'placeholder'=>'Contenu de Question',
                    'class'=>'prompt srch_explore',
                    'name'=>'Question',
                    'id'=>'Question',
                    'maxlength'=>'64',
                ]
            ])
            ->add('nomb_rep',TextType::class,[
                'attr'=>[
                    'required'   => true,
                    'placeholder'=>'Nombre des Réponse',
                    'class'=>'prompt srch_explore',
                    'name'=>'Réponse',
                    'id'=>'Réponse',
                    'maxlength'=>'64',
                ]
            ])
            ;
//            ->add('rep_just')
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Question::class,
        ]);
    }
}
<?php

namespace App\Form;

use App\Entity\Question;
use App\Entity\Answer;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QuestionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('content')
            ->add('mediaurl')
            ->add('goodanswer', EntityType::class, [
                'class' => Answer::class,
                'choice_label' => 'content',
            ])
            ->add('answers', EntityType::class, [
                'class' => Answer::class,
                'choice_label' => 'content',
                'multiple' => true,
                'expanded' => true,
            ]);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Question::class,
        ]);
    }
}

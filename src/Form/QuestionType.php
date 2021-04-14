<?php

namespace App\Form;

use App\Entity\Question;
use App\Entity\Answer;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class QuestionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
        $builder
            ->add('title')
            ->add('content')
            // ->add('mediaurl')
            ->add('upload_file', FileType::class, [
                'label' => false,
                'mapped' => false, // Tell that there is no Entity to link
                'required' => true,
                'constraints' => [
                  new File()
                ],
              ])
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

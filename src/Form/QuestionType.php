<?php

namespace App\Form;

use App\Entity\Question;
use App\Entity\Answer;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class QuestionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('content')
            ->add('mediaurl')
            ->add('answers', CollectionType::class, [
                'entry_type' => AnswerType::class,
                'entry_options' => ['label' => false],
            ]);
    
            /*->add('answers', EntityType::class, [
                // looks for choices from this entity
                'class' => Answer::class,
            
                // uses the User.username property as the visible option string
                'choice_label' => 'content',
            
                // used to render a select box, check boxes or radios
                'multiple' => true,
                'expanded' => true,
            ]);*/
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Question::class,
        ]);
    }
}

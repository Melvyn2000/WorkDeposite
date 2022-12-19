<?php

namespace App\Form;

use App\Entity\Works;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class WorksType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'attr' => [
                    'class' => 'search-input',
                    'placeholder' => 'Write a new title...'
                ]
            ])
            ->add('description', TextType::class, [
                'attr' => [
                    'class' => 'search-input',
                    'placeholder' => 'Write a new description...'
                ]
            ])
            //->add('img')
            ->add('filesOrlinks', FileType::class, [
                'attr' => ['class' => 'search-input'],
                'required' => false,
                'mapped' => false,
            ])
            ->add('categories');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Works::class,
        ]);
    }
}

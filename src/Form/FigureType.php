<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Figure;
use App\Entity\Description;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;

class FigureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom'
            ])
            ->add('description', TextareaType::class, [
                'attr' => ['rows' => '15'],
            ])
            ->add('type', EntityType::class, [
                'label' => 'Groupes',
                'class' => Description::class,
                'choice_label' => 'description'

            ])
            ->add('images', FileType::class, [
                'label' => 'Image(s)',
                'multiple' => true,
                'mapped' => false,
                'required' => false
            ])
            ->add('media', UrlType::class, [
                'label' => 'video (ex: https://youtu.be/SDdfIqJLrq4)',
                'mapped' => false,
                'required' => false,

            ])
            ->add('main', HiddenType::class, [
                'mapped' => false,
                'required' => false,

            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Figure::class,
        ]);
    }
}

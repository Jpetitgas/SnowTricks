<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Figure;
use App\Entity\category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Url;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

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
                'class' => category::class,
                'choice_label' => 'category'

            ])
            ->add('images', FileType::class, [
                'label' => 'Image(s)',
                'multiple' => true,
                'mapped' => false,
                'required' => false
            ])
            ->add('media', UrlType::class, [
                'label' => 'video',
                'mapped' => false,
                'required' => false,
                'help' => 'Exemple de format: https://youtu.be/SDdfIqJLrq4',
                'constraints' => [
                    new Url(['message' => 'Cette url n\'est pas valide']),
                    new Regex(
                        [
                            'pattern' => '^https:\/\/youtu.be\/[a-zA-Z0-9-_]+^',
                            'message' => 'Merci de rentrer une url valide',
                        ]
                    ),
                ],

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

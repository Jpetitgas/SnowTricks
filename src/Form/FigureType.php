<?php

namespace App\Form;

use App\Entity\Description;
use App\Entity\Figure;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FigureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('desciption', EntityType::class, [
                'label' => 'groupe',
                'placeholder' => '--Choisir un groupe--',
                'class' => Description::class,
                'choice_label' => function (Description $description) {
                    return $description->getDescription();
                }
            ])
            ->add('date')
            ->add('mainpicture')
            ->add('slug')
            ->add('date_mod')
            ->add('type');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Figure::class,
        ]);
    }
}

<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProfilType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('avatar', FileType::class, [
            'label' => 'Photo de profil',
            'mapped' => false,
            'required' => false,
        ])
        ->add('username', TextType::class, [
            'attr'=>[
                'class'=>"w-full mt-1 bg-transparent border border-gray-600 rounded px-4 py-2 focus:outline-none focus:border-blue-500 text-white"
            ],
            'label_attr'=>[
                'class'=>"text-sm text-gray-400"
            ]
        ])
        ->add('description', TextareaType::class,[
            'attr'=>[
                'class'=>"w-full mt-1 bg-transparent border border-gray-600 rounded px-4 py-2 focus:outline-none focus:border-blue-500 text-white"
            ],
            'label_attr'=>[
                'class'=>"text-sm text-gray-400"
            ]
        ]);
        // ->add('website', TextType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'required' => false,
        ]);
    }
}

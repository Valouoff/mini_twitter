<?php

namespace App\Form;

use App\Entity\Tweet;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TweetType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('content')
            ->add('imageFile', FileType::class, [
                'label' => 'Ajouter une image',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '200M',
                        'mimeTypes' => ['image/jpeg', 'image/png', 'image/webp', 'image/gif', 'video/mp4'],
                        'mimeTypesMessage' => 'Merci de choisir une image au format JPG, PNG, WebP.',
                    ]),
                ],
                'attr' => ['accept' => 'image/*, video/mp4'],
            ]);;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Tweet::class,
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            'csrf_token_id'   => 'user_item',
            'required' => false,
        ]);
    }
}

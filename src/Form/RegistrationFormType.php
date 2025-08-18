<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotCompromisedPassword;
use Symfony\Component\Validator\Constraints\Regex;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            ->add('username', TextType::class, [
                'label' => 'Pseudo',
            ])
            ->add('email')
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => "Vous des accepter les comditions générales d'utilisation.",
                    ]),
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank(['message' => 'Le mot de passe est obligatoire.']),
                    new Length([
                        'min' => 8,
                        'max' => 64,
                        'minMessage' => 'Au moins {{ limit }} caractères.',
                        'maxMessage' => 'Au plus {{ limit }} caractères.',
                    ]),
                    new Regex(['pattern' => '/[a-z]/',      'message' => 'Ajoutez au moins une lettre minuscule.']),
                    new Regex(['pattern' => '/[A-Z]/',      'message' => 'Ajoutez au moins une lettre majuscule.']),
                    new Regex(['pattern' => '/\d/',         'message' => 'Ajoutez au moins un chiffre.']),
                    new Regex(['pattern' => '/[^\w\s]/',    'message' => 'Ajoutez au moins un caractère spécial.']),
                    new Regex(['pattern' => '/^\S+$/',      'message' => 'Ne mettez pas d’espace.']),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            'csrf_token_id'   => 'user_item',
            'required' => false,
        ]);
    }
}

<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type as Type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName', Type\TextType::class)
            ->add('lastName', Type\TextType::class, ['required' => false])
            ->add('email', Type\EmailType::class)
            ->add('password', Type\PasswordType::class)
            ->add('cgu', Type\CheckboxType::class,
                ['label' => 'You should accept the <a href="#">terms and conditions</a> of this website',
                'label_html' => true,
                'mapped' => false]
            )
            ->add('register', Type\SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}

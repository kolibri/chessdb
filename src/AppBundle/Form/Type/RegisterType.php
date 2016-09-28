<?php


namespace AppBundle\Form\Type;

use AppBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class)
            ->add('emailAddress', EmailType::class)
            ->add('playerAliases', PlayerAliasesType::class)
            ->add(
                'rawPassword',
                RepeatedType::class,
                [
                    'type' => PasswordType::class,
                    'first_options' => ['label' => 'form.register.label.password_first'],
                    'second_options' => ['label' => 'form.register.label.password_second'],
                ]
            );

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => User::class,
                'validation_groups' => ['registration'],
                'empty_data' => function (FormInterface $form) {
                    return User::register(
                        $form->get('username')->getData(),
                        $form->get('emailAddress')->getData(),
                        $form->get('rawPassword')->getData(),
                        $form->get('playerAliases')->getData()
                    );
                },
            ]
        );
    }
}

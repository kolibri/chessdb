<?php


namespace AppBundle\Form;


use AppBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class)
            ->add('emailAddress', EmailType::class)
            ->add('playerAliases', TextType::class);

        $builder->get('playerAliases')->addModelTransformer(
            new CallbackTransformer(
                function ($aliasesAsArray) {
                    return implode(',', $aliasesAsArray);
                },
                function ($aliasesAsString) {
                    return array_map(
                        function ($item) {
                            return trim($item);
                        },
                        explode(',', $aliasesAsString)
                    );
                }
            )
        );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => User::class,
            ]
        );
    }
}
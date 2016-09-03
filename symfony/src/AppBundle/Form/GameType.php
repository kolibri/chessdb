<?php


namespace AppBundle\User;


use AppBundle\Entity\Game;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GameType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('pgn', TextareaType::class, [
            'attr' => [
                'rows' => 20,
                'cols' => 60,
            ]
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        return [
            'data-class' => Game::class
        ];
    }


}
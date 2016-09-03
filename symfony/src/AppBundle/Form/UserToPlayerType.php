<?php


namespace AppBundle\Form;

use AppBundle\Entity\Player;
use AppBundle\Entity\PlayerRepository;
use AppBundle\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserToPlayerType extends AbstractType
{
    /** @var  PlayerRepository */
    private $playerRepository;

    /**
     * UserToPlayerType constructor.
     * @param PlayerRepository $playerRepository
     */
    public function __construct(PlayerRepository $playerRepository)
    {
        $this->playerRepository = $playerRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('players', EntityType::class, [
                'class' => Player::class,
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true,
                'by_reference' => false,
                'query_builder' => $this
                    ->playerRepository
                    ->createCanAssignedToUserQueryBuilder($builder->getData())
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class
        ]);
    }
}
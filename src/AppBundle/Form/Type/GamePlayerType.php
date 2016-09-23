<?php


namespace AppBundle\Form\Type;

use AppBundle\Entity\Repository\UserRepository;
use AppBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GamePlayerType extends AbstractType
{
    /** @var UserRepository */
    private $userRepository;

    /**
     * GamePlayerType constructor.
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'attr' => [
                    'data-suggestions' => implode(
                        ',',
                        array_map(
                            function (User $user) {
                                return $user->getUsername();
                            },
                            $this->userRepository->findAll()
                        )
                    ),
                ],
            ]
        );
    }

    public function getParent()
    {
        return TextType::class;
    }
}

<?php


namespace AppBundle\Form;

use AppBundle\Entity\Repository\UserRepository;
use AppBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class GamePlayerType extends AbstractType
{
    /** @var TokenStorage */
    private $tokenStorage;

    /** @var UserRepository */
    private $userRepository;

    /**
     * GamePlayerType constructor.
     * @param TokenStorage $tokenStorage
     * @param UserRepository $userRepository
     */
    public function __construct(TokenStorage $tokenStorage, UserRepository $userRepository)
    {
        $this->tokenStorage = $tokenStorage;
        $this->userRepository = $userRepository;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $aliases = [];
        if ($token = $this->tokenStorage->getToken()) {
            $user = $token->getUser();

            if ($user instanceof User) {
                $aliases = array_unique(array_merge($aliases, $user->getPlayerAliases()));
            }
        }

        $aliases = array_unique(array_merge(
            $aliases,
            array_map(function (User $user) {
                return $user->getUsername();
            }, $this->userRepository->findAll())
        ));

        $resolver->setDefaults(
            [
                'attr' => ['data-suggestions' => implode(',', $aliases)],
            ]
        );
    }

    public function getParent()
    {
        return TextType::class;
    }
}

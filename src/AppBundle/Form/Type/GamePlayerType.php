<?php declare(strict_types = 1);

namespace AppBundle\Form\Type;

use AppBundle\Entity\Repository\UserRepository;
use AppBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GamePlayerType extends AbstractType
{
    private $userRepository;

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

    public function getParent(): string
    {
        return TextType::class;
    }
}

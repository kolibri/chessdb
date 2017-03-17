<?php declare(strict_types = 1);

namespace AppBundle\Helper;

use AppBundle\Entity\Repository\UserRepository;
use AppBundle\Entity\User;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;

class RegistrationHelper
{
    private $encoderFactory;
    private $repository;

    public function __construct(EncoderFactoryInterface $encoderFactory, UserRepository $repository)
    {
        $this->encoderFactory = $encoderFactory;
        $this->repository = $repository;
    }

    public function encodePassword(User $user): User
    {
        $encoder = $this
            ->encoderFactory
            ->getEncoder(User::class);
        $encodedPassword = $encoder
            ->encodePassword($user->getRawPassword(), null);
        $user
            ->setPassword($encodedPassword);

        return $user;
    }

    public function encodePasswordAndSave(User $user)
    {
        $this
            ->repository
            ->save(
                $this->encodePassword($user)
            );
    }
}

<?php

namespace AppBundle\Helper;

use AppBundle\Entity\Repository\UserRepository;
use AppBundle\Entity\User;
use Doctrine\Common\Persistence\ObjectRepository;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;

class RegistrationHelper
{
    /** @var EncoderFactoryInterface */
    private $encoderFactory;

    /** @var UserRepository */
    private $repository;

    /**
     * RegistrationHandler constructor.
     *
     * @param EncoderFactoryInterface $encoderFactory
     * @param UserRepository          $repository
     */
    public function __construct(EncoderFactoryInterface $encoderFactory, UserRepository $repository)
    {
        $this->encoderFactory = $encoderFactory;
        $this->repository = $repository;
    }

    public function handleRegistration(User $user)
    {
        $encoder = $this->encoderFactory->getEncoder(User::class);
        $encodedPassword = $encoder->encodePassword($user->getRawPassword(), null);
        $user->setPassword($encodedPassword);

        $this->repository->save($user);
    }
}

<?php

namespace AppBundle\Form;

use AppBundle\Entity\User;
use AppBundle\Entity\UserRepository;
use AppBundle\Mailer\UserMailer;
use Doctrine\Common\Persistence\ObjectRepository;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;

class UserRegistrationHandler
{
    /** @var EncoderFactoryInterface */
    private $encoderFactory;

    /** @var UserRepository */
    private $repository;

    /** @var UserMailer  */
    private $mailer;

    /**
     * UserRegistrationHandler constructor.
     *
     * @param EncoderFactoryInterface $encoderFactory
     * @param UserRepository          $repository
     * @param UserMailer              $mailer
     */
    public function __construct(EncoderFactoryInterface $encoderFactory, UserRepository $repository, UserMailer $mailer)
    {
        $this->encoderFactory = $encoderFactory;
        $this->repository = $repository;
        $this->mailer = $mailer;
    }

    public function handle(User $user)
    {
        $encoder = $this->encoderFactory->getEncoder(User::class);
        $encodedPassword = $encoder->encodePassword($user->getRawPassword(), null);
        $user->setPassword($encodedPassword);

        $this->repository->save($user);

        $this->mailer->sendNewRegistrationMail($user);
    }
}

<?php

namespace AppBundle\User;

use AppBundle\Entity\User;
use AppBundle\Entity\UserRepository;
use AppBundle\Mailer\UserMailer;
use Doctrine\Common\Persistence\ObjectRepository;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;

class RegistrationHandler
{
    /** @var EncoderFactoryInterface */
    private $encoderFactory;

    /** @var UserRepository */
    private $repository;

    /** @var UserMailer  */
    private $mailer;

    /**
     * RegistrationHandler constructor.
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

    public function handleRegistration(User $user, $sendMail = true)
    {
        $encoder = $this->encoderFactory->getEncoder(User::class);
        $encodedPassword = $encoder->encodePassword($user->getRawPassword(), null);
        $user->setPassword($encodedPassword);

        $this->repository->save($user);

        if ($sendMail) {
            $this->mailer->sendNewRegistrationMail($user);
        }
    }
}

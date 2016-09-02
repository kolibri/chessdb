<?php

namespace AppBundle\Mailer;

use AppBundle\Entity\User;

class UserMailer
{
    /** @var \Swift_Mailer  */
    private $mailer;

    /** @var array */
    private $adminAddress;

    /** @var array */
    private $senderAddress;

    /**
     * UserMailer constructor.
     *
     * @param \Swift_Mailer $mailer
     * @param array         $adminAddress
     * @param array         $senderAddress
     */
    public function __construct(\Swift_Mailer $mailer, array $adminAddress, array $senderAddress)
    {
        $this->mailer = $mailer;
        $this->adminAddress = $adminAddress;
        $this->senderAddress = $senderAddress;
    }

    /**
     * @param User $user
     */
    public function sendNewRegistrationMail(User $user)
    {
        $message = new \Swift_Message();
        $message->setTo($this->adminAddress);
        $message->setFrom($this->senderAddress);
        $message->setSubject('There is a new User');
        $message->setBody(sprintf('There is a new User named "%s"', $user->getUsername()));

        $this->mailer->send($message);
    }
}

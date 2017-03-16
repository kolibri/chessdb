<?php declare(strict_types = 1);

namespace AppBundle\Command;

use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class CreateUserCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('app:user:create')
            ->setDescription('creates a new user')
            ->addArgument('name', InputArgument::REQUIRED, 'name of the user')
            ->addArgument('emailAddress', InputArgument::REQUIRED, 'email address of the user')
            ->addArgument('password', InputArgument::REQUIRED, 'password for the user')
            ->addOption('isAdmin', 'a', InputOption::VALUE_NONE, 'set the user as admin');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $username = $input->getArgument('name');
        $emailAddress = $input->getArgument('emailAddress');
        $pasword = $input->getArgument('password');
        $isAdmin = $input->getOption('isAdmin');

        $user = User::register($username, $emailAddress, $pasword);

        if ($isAdmin) {
            $user->addRole('ROLE_ADMIN');
        }

        $userHandler = $this->getContainer()->get('app.helper.registration_helper');
        $userHandler->encodePasswordAndSave($user);
    }
}

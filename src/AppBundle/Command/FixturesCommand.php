<?php declare(strict_types = 1);

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class FixturesCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('app:fixtures')
            ->setDescription('load fixtures')
            ->addArgument('fixture', InputArgument::OPTIONAL, 'fixture path', 'dev');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->getContainer()->get('app.fixtures.loader')->loadFixtures(
            sprintf(
                '%s/Resources/fixtures/%s.yml',
                $this
                    ->getContainer()
                    ->getParameter('kernel.root_dir'),
                $input->getArgument('fixture')
            )
        );
    }
}

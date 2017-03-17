<?php declare(strict_types = 1);

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class FixturesCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('app:fixtures')
            ->setDescription('load fixtures');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $fixturePath = $this
                ->getContainer()
                ->getParameter('kernel.root_dir').'/Resources/fixtures/dev.yml';

        $this->getContainer()->get('app.fixtures.loader')->loadFixtures($fixturePath);
    }
}

<?php

namespace AppBundle\Command;

use AppBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\SchemaTool;
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

        $manager = $this
            ->getContainer()
            ->get('doctrine')
            ->getManager();

        $metadatas = $manager
            ->getMetadataFactory()
            ->getAllMetadata();

        if (empty($metadatas)) {
            $output->writeln('No Metadata Classes to process.');

            return 1;
        }
        $tool = new SchemaTool($manager);

        $tool->dropSchema($metadatas);
        $tool->createSchema($metadatas);
        $output->writeln('Deleted and created database.');

        /** @var EntityManager $manager */
        $persister = new \Nelmio\Alice\Persister\Doctrine($manager);
        $loader = new \Nelmio\Alice\Fixtures\Loader();
        $loader->setPersister($persister);

        $objects = $loader->load($fixturePath);

        $userHandler = $this->getContainer()->get('app.helper.registration_helper');

        foreach ($objects as $object) {
            if (!$object instanceof User) {
                continue;
            }
            //$output->writeln(sprintf('Add user: %s password: %s', $object->getUsername(), $object->getRawPassword()));
            $userHandler->encodePassword($object);
        }

        $output->writeln('Fixtures loaded ...');

        $persister->persist($objects);
        $output->writeln('and persisted!');
    }
}

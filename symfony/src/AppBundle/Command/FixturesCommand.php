<?php


namespace AppBundle\Command;

use AppBundle\Entity\User;
use AppBundle\Faker\Provider\ChessProvider;
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
        $output->writeln('No Metadata Classes to process.');

        /** @var EntityManager $manager */
        $persister = new \Nelmio\Alice\Persister\Doctrine($manager);
        $loader = new \Nelmio\Alice\Fixtures\Loader();
        $loader->addProvider(new ChessProvider());
        $loader->setPersister($persister);

        $objects = $loader->load($fixturePath);

        $userHandler = $this->getContainer()->get('app.helper.registration_helper');

        foreach ($objects as $object) {
            if (!$object instanceof User) {
                continue;
            }
            $userHandler->handleRegistration($object);
        }

        $persister->persist($objects);
    }
}

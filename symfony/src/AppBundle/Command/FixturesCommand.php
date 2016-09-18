<?php


namespace AppBundle\Command;


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

        if (!empty($metadatas)) {
            $tool = new SchemaTool($manager);

            $tool->dropSchema($metadatas);
            $tool->createSchema($metadatas);
        } else {
            $output->writeln('No Metadata Classes to process.');

            return 0;
        }

        /** @var EntityManager $manager */
        $persister = new \Nelmio\Alice\Persister\Doctrine($manager);
        $loader = new \Nelmio\Alice\Fixtures\Loader();
        $loader->setPersister($persister);

        $objects = $loader->load($fixturePath);
        $persister->persist($objects);

    }
}

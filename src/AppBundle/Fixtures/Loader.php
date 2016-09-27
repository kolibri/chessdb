<?php

namespace AppBundle\Fixtures;

use AppBundle\Entity\User;
use AppBundle\Helper\RegistrationHelper;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\Tools\SchemaTool;
use Nelmio\Alice\Persister\Doctrine;

class Loader
{
    /** @var ObjectManager */
    private $manager;

    /** @var  RegistrationHelper */
    private $registrationHelper;

    /**
     * Loader constructor.
     * @param ObjectManager $manager
     * @param RegistrationHelper $registrationHelper
     */
    public function __construct(ObjectManager $manager, RegistrationHelper $registrationHelper)
    {
        $this->manager = $manager;
        $this->registrationHelper = $registrationHelper;
    }

    public function loadFixtures($fixturePath)
    {
        $manager = $this->manager;

        $metadatas = $manager
            ->getMetadataFactory()
            ->getAllMetadata();

        if (empty($metadatas)) {
//            $output->writeln('No Metadata Classes to process.');

            return 1;
        }
        $tool = new SchemaTool($manager);

        $tool->dropSchema($metadatas);
        $tool->createSchema($metadatas);
//        $output->writeln('Recreated database schema');

        $persister = new Doctrine($manager);
        $loader = new \Nelmio\Alice\Fixtures\Loader();
        $loader->setPersister($persister);

        $objects = $loader->load($fixturePath);

        $userHandler = $this->registrationHelper;

        foreach ($objects as $object) {
            if (!$object instanceof User) {
                continue;
            }
//            $output->writeln(sprintf('Add user: %s password: %s', $object->getUsername(), $object->getRawPassword()));
            $userHandler->encodePassword($object);
        }

//        $output->writeln('Fixtures loaded ...');

        $persister->persist($objects);
//        $output->writeln('and persisted!');
    }
}
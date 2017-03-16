<?php declare(strict_types = 1);

namespace AppBundle\Fixtures;

use AppBundle\Entity\User;
use AppBundle\Helper\RegistrationHelper;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\Tools\SchemaTool;
use Nelmio\Alice\Persister\Doctrine;

class Loader
{
    private $manager;
    private $registrationHelper;

    public function __construct(ObjectManager $manager, RegistrationHelper $registrationHelper)
    {
        $this->manager = $manager;
        $this->registrationHelper = $registrationHelper;
    }

    public function loadFixtures(string $fixturePath)
    {
        $manager = $this->manager;

        $metadata = $manager
            ->getMetadataFactory()
            ->getAllMetadata();

        if (empty($metadata)) {

            return 1;
        }
        $tool = new SchemaTool($manager);

        $tool->dropSchema($metadata);
        $tool->createSchema($metadata);

        $persister = new Doctrine($manager);
        $loader = new \Nelmio\Alice\Fixtures\Loader();
        $loader->setPersister($persister);

        $objects = $loader->load($fixturePath);

        $userHandler = $this->registrationHelper;

        foreach ($objects as $object) {
            if (!$object instanceof User) {
                continue;
            }
            $userHandler->encodePassword($object);
        }

        $persister->persist($objects);
    }
}

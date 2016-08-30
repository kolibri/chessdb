<?php

namespace AppBundle\Form;

use AppBundle\Import\PgnStringToGameImporter;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\Form;

class GameTypeHandler
{
    /** @var  PgnStringToGameImporter */
    private $importer;

    /** @var ObjectManager */
    private $entityManager;

    /**
     * GameTypeHandler constructor.
     * @param PgnStringToGameImporter $importer
     * @param ObjectManager $entityManager
     */
    public function __construct(PgnStringToGameImporter $importer, ObjectManager $entityManager)
    {
        $this->importer = $importer;
        $this->entityManager = $entityManager;
    }


    public function handle(Form $form)
    {
        $game = $this->importer->updateGame(
            $form->getData(),
            $form->getData()->getPgn()
        );

        $this->entityManager->persist($game);
        $this->entityManager->flush();
    }
}
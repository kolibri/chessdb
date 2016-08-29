<?php


namespace AppBundle\Import;


use Doctrine\Common\Persistence\ObjectManager;
use Psr\Log\LoggerInterface;
use Symfony\Component\Finder\Finder;

class PgnFilesImporter
{
    /** @var PgnStringToGameImporter */
    private $importer;
    /** @var  ObjectManager */
    private $entityManager;
    /** @var Finder */
    private $finder;

    /**
     * PgnFilesImporter constructor.
     * @param PgnStringToGameImporter $importer
     * @param ObjectManager $entityManager
     * @param Finder $finder
     */
    public function __construct(PgnStringToGameImporter $importer, ObjectManager $entityManager, Finder $finder)
    {
        $this->importer = $importer;
        $this->entityManager = $entityManager;
        $this->finder = $finder;
    }

    public function importDirectory($directory, LoggerInterface $logger = null)
    {
        if (!file_exists($directory)) {
            if ($logger) {
                $logger->error(sprintf('Directory "%s" does not exist!', $directory));
            }

            // @todo: throw exception(?)
            return false;
        }

        $files = $this->finder->files()->in($directory)->name('*.pgn');

        if (0 === $files->count()) {
            if ($logger) {
                $logger->error(sprintf('No PGN files found in "%s"!', $directory));
            }

            return false;
        }

        foreach ($files as $file) {
            $game = $this
                ->importer
                ->createChessGame(
                    $file->getContents()
                );

            $this
                ->entityManager
                ->persist($game);
            if ($logger) {
                $logger->info(sprintf('File "%s" imported', $file->getRelativePathname()));
            }
        }

        $this->entityManager->flush();
        
        if ($logger) {
            $logger->info(sprintf('Finished! %s files imported', $files->count()));
        }
    }
}
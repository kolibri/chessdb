<?php


namespace AppBundle\Import;


use Doctrine\Common\Persistence\ObjectManager;
use Dropbox\Client;
use Psr\Log\LoggerInterface;

class DropBoxImporter
{
    /** @var PgnStringToGameImporter */
    private $importer;
    /** @var ObjectManager */
    private $entityManager;
    /** @var Client */
    private $dropboxClient;
    /** @var string */
    private $importedPath;
    /** @var string */
    private $tmpFilePath;

    /**
     * DropBoxImporter constructor.
     * @param PgnStringToGameImporter $importer
     * @param ObjectManager $entityManager
     * @param Client $dropboxClient
     * @param string $importedPath
     * @param string $tmpFilePath
     */
    public function __construct(PgnStringToGameImporter $importer, ObjectManager $entityManager, Client $dropboxClient, $importedPath, $tmpFilePath)
    {
        $this->importer = $importer;
        $this->entityManager = $entityManager;
        $this->dropboxClient = $dropboxClient;
        $this->importedPath = $importedPath;
        $this->tmpFilePath = $tmpFilePath;
    }


    public function importPgns($move = true, LoggerInterface $logger = null)
    {
        $metadata = $this->dropboxClient->getMetadataWithChildren('/');

        $fileCount = 0;

        foreach ($metadata['contents'] as $item) {
            if ($item['is_dir']) {
                continue;
            }

            $path = $item['path'];

            if (preg_match('/.*\.pgn$/i', $path)) {
                $this
                    ->dropboxClient
                    ->getFile($path, fopen($this->tmpFilePath, 'wb'));
                $this
                    ->entityManager
                    ->persist(
                        $this
                            ->importer
                            ->createChessGame(file_get_contents($this->tmpFilePath))
                    );

                $fileCount++;

                if ($logger) {
                    $logger->info(sprintf('File "%s" imported', $path));
                }

                if ($move) {
                    $targetPath = $this->importedPath . $path;
                    $this
                        ->dropboxClient
                        ->move(
                            $path,
                            $targetPath
                        );

                    if ($logger) {
                        $logger->info(
                            sprintf('Moved file "%s" to "%s"', $path, $targetPath)
                        );
                    }
                }
            }
        }

        $this->entityManager->flush();
        if ($logger) {
            $logger->info(sprintf('Finished! %s files imported', $fileCount));
        }
    }
}
<?php declare(strict_types = 1);

namespace AppBundle\Dropbox;

class Client
{
    private $client;

    public function __construct(\Dropbox\Client $client)
    {
        $this->client = $client;
    }

    public function getFileContent(string $path): string
    {
        $tmpFileName = tempnam('/tmp', 'dropbox-import-');
        $tmpFile = fopen($tmpFileName, 'wb');
        $this->client->getFile($path, $tmpFile);

        return file_get_contents($tmpFileName);
    }

    public function getFilePaths(string $directory, string $pattern = '/.*/', bool $withDirs = false): array
    {

        $metadata = $this->client->getMetadataWithChildren($directory);

        $files = [];

        foreach ($metadata['contents'] as $content) {
            if ($content['is_dir'] && $withDirs === false) {
                continue;
            }

            $path = $content['path'];

            if (!preg_match($pattern, $path)) {
                continue;
            }

            $files[] = $path;
        }

        return $files;
    }
}

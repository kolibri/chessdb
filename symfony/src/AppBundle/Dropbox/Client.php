<?php


namespace AppBundle\Dropbox;

class Client
{
    /** @var  \Dropbox\Client */
    private $client;

    /**
     * Client constructor.
     * @param \Dropbox\Client $client
     */
    public function __construct(\Dropbox\Client $client)
    {
        $this->client = $client;
    }

    /**
     * @param $path
     * @return mixed
     * @throws \Dropbox\Exception_BadResponseCode
     * @throws \Dropbox\Exception_OverQuota
     * @throws \Dropbox\Exception_RetryLater
     * @throws \Dropbox\Exception_ServerError
     */
    public function getFileContent($path)
    {
        $tmpFileName = tempnam('/tmp', 'dropbox-import-');
        $tmpFile = fopen($tmpFileName, 'wb');
        $this->client->getFile($path, $tmpFile);

        return file_get_contents($tmpFileName);
    }

    /**
     * @param $directory
     * @param string $pattern
     * @param bool $withDirs
     * @return array
     */
    public function getFilePaths($directory, $pattern = '/.*/', $withDirs = false)
    {

        $metadata = $this->client->getMetadataWithChildren($directory);

        $files = [];

        foreach ($metadata['contents'] as $content) {
            if ($content['is_dir'] and $withDirs === false) {
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

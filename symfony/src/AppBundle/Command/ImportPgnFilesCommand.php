<?php


namespace AppBundle\Command;

use Psr\Log\LogLevel;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Logger\ConsoleLogger;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

class ImportPgnFilesCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('pgn:import:files')
            ->setDescription('Imports a given PGN file')
            ->addArgument('directory', InputArgument::REQUIRED, 'A directory, containing pgn files');

    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this
            ->getContainer()
            ->get('app.import.pgn_files_importer')
            ->importDirectory(
                $input->getArgument('directory'),
                new ConsoleLogger($output, [LogLevel::INFO => OutputInterface::VERBOSITY_NORMAL]
                )
            );
    }
}

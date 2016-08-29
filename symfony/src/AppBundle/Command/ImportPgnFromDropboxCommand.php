<?php


namespace AppBundle\Command;

use Psr\Log\LogLevel;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Logger\ConsoleLogger;
use Symfony\Component\Console\Output\OutputInterface;

class ImportPgnFromDropboxCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('pgn:import:dropbox')
            ->addOption('keep-files', 'k', InputOption::VALUE_NONE, 'Do not move files when specified')
            ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this
            ->getContainer()
            ->get('app.import.drop_box_importer')
            ->importPgns(
                !$input->getOption('keep-files'),
                new ConsoleLogger($output, [LogLevel::INFO => OutputInterface::VERBOSITY_NORMAL]
                )
            );
    }
}

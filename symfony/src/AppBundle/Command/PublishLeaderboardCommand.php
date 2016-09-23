<?php


namespace AppBundle\Command;

use AppBundle\Entity\Leaderboard;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PublishLeaderboardCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('app:leaderboard')
            ->setDescription('Publishs a new leaderboard based on current data');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $leaderboardRepository = $this
            ->getContainer()
            ->get('doctrine')
            ->getRepository(Leaderboard::class);

        $leaderboardRepository
            ->save($leaderboardRepository->createLeaderboard());
    }
}

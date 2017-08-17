<?php
declare(strict_types=1);

namespace Search2d\Presentation\Cli\Command\Pixiv;

use League\Tactician\CommandBus;
use Search2d\Usecase\Pixiv\HandleRequestRankingCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RequestRankingWorkerCommand extends Command
{
    /** @var \League\Tactician\CommandBus */
    private $commandBus;

    /**
     * @param \League\Tactician\CommandBus $commandBus
     */
    public function __construct(CommandBus $commandBus)
    {
        parent::__construct();

        $this->commandBus = $commandBus;
    }

    /**
     * @return void
     */
    protected function configure(): void
    {
        $this
            ->setName('pixiv:request-ranking-worker')
            ->setDescription('ランキング取得要求を処理するワーカー');
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @return int|null
     */
    protected function execute(InputInterface $input, OutputInterface $output): ?int
    {
        while (true) {
            $this->commandBus->handle(new HandleRequestRankingCommand());
        }

        return 0;
    }
}
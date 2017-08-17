<?php
declare(strict_types=1);

namespace Search2d\Presentation\Cli\Command\Pixiv;

use Cake\Chronos\Date;
use League\Tactician\CommandBus;
use Search2d\Domain\Pixiv\RankingDate;
use Search2d\Domain\Pixiv\RankingMode;
use Search2d\Usecase\Pixiv\SendRequestRankingCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RequestRankingCommand extends Command
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
            ->setName('pixiv:request-ranking')
            ->addArgument('mode', InputArgument::REQUIRED, 'ランキング種別')
            ->addArgument('date', InputArgument::OPTIONAL, 'ランキング日付', null)
            ->setDescription('ランキング取得要求を送信する');
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @return int|null
     */
    protected function execute(InputInterface $input, OutputInterface $output): ?int
    {
        $mode = new RankingMode($input->getArgument('mode'));

        if ($date = $input->getArgument('date')) {
            $date = new RankingDate(new Date($date));
        } else {
            $date = RankingDate::latest();
        }

        $output->writeln(sprintf(
            'ランキング取得要求を送信 種別:%s 日付:%s', $mode->value, $date->value
        ));

        $this->commandBus->handle(new SendRequestRankingCommand($mode, $date));

        return 0;
    }
}
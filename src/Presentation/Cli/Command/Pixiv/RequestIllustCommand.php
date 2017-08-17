<?php
declare(strict_types=1);

namespace Search2d\Presentation\Cli\Command\Pixiv;

use League\Tactician\CommandBus;
use Search2d\Usecase\Pixiv\SendRequestIllustCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RequestIllustCommand extends Command
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
            ->setName('pixiv:request-illust')
            ->addArgument('illust_id', InputArgument::REQUIRED, 'イラストID')
            ->setDescription('イラスト取得要求を送信する');
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @return int|null
     */
    protected function execute(InputInterface $input, OutputInterface $output): ?int
    {
        $illustId = (int)$input->getArgument('illust_id');

        $output->writeln(sprintf(
            'イラスト取得要求を送信 イラストID:%d',
            $illustId
        ));

        $this->commandBus->handle(new SendRequestIllustCommand($illustId));

        return 0;
    }
}
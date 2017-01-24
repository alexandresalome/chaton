<?php

namespace Chaton\Command;

use Chaton\Chat\ConsoleChat;
use Chaton\Chaton;
use React\EventLoop\Factory;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Command to execute chaton with a command line.
 */
class ChatCommand extends Command
{
    private $chaton;

    public function __construct(Chaton $chaton)
    {
        parent::__construct();

        $this->chaton = $chaton;
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('chat')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $chat = new ConsoleChat($input, $output);
        $this->chaton->addChat($chat);
        $this->chaton->run();
    }
}

<?php

namespace Chaton\Command;

use Chaton\Chat\ConsoleChat;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Command to execute chaton with a command line.
 */
class ChatCommand extends ContainerAwareCommand
{
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
        $chaton = $this->getContainer()->get('chaton');

        $chat = new ConsoleChat($input, $output);
        $chaton->addChat($chat);
        $chaton->run();
    }
}

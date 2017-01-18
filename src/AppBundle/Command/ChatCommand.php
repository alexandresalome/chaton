<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ChatCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('chat')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $chaton = $this->getContainer()->get('chaton');
        $chat = $chaton->getChat();
        $chat->setConsole($input, $output);
        $chaton->run();
    }
}

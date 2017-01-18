<?php

namespace AppBundle\Chat\Chat;

use AppBundle\Chat\Channel;
use AppBundle\Chat\ChatInterface;
use AppBundle\Chat\Message;
use AppBundle\Chat\User;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ConsoleChat implements ChatInterface
{
    private $input;
    private $output;

    public function setConsole(InputInterface $input, OutputInterface $output)
    {
        $this->input = $input;
        $this->output = $output;
    }

    /**
     * @return InputInterface
     */
    public function getInput()
    {
        if (!$this->input) {
            throw new \RuntimeException('No input injected in console chat.');
        }

        return $this->input;
    }

    /**
     * @return OutputInterface
     */
    public function getOutput()
    {
        if (!$this->output) {
            throw new \RuntimeException('No output injected in console chat.');
        }

        return $this->output;
    }

    /**
     * Channel[]
     */
    public function getChannels(): array
    {
    }

    /**
     * @return User[]
     */
    public function getUsers(Channel $channel): array
    {
    }

    public function sendMessage(Message $message)
    {
    }

    public function onMessage(callable $callback)
    {
    }

    public function connect()
    {
        $output = $this->getOutput();
        $output->writeln("Chat started");
    }

    public function tick()
    {
    }
}

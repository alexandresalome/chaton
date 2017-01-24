<?php

namespace Chaton\Chat;

use Chaton\Model\Channel;
use Chaton\Model\ChatInterface;
use Chaton\Model\Message;
use Chaton\Model\User;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ConsoleChat implements ChatInterface
{
    private $input;
    private $output;

    public function __construct(InputInterface $input, OutputInterface $output)
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

    public function connect()
    {
        $output = $this->getOutput();
        $output->writeln("Model started");
    }

    /**
     * Channel[]
     */
    public function getChannels(): array
    {
        return [
            new Channel('default'),
        ]
    }

    /**
     * @return User[]
     */
    public function getUsers(Channel $channel): array
    {
        if ($channel->getId() == 'default') {
            return [
                new User("chaton"),
                new User("user"),
            ];
        }
    }

    public function sendMessage(Message $message)
    {
    }

    public function onMessage(callable $callback)
    {
    }

    /**
     * Returns list of users present on a channel.
     *
     * @return User[]
     */
    public function getAllUsers(): array
    {
        // TODO: Implement getAllUsers() method.
    }

    /**
     * Returns list of received messages.
     *
     * @return Message[]
     */
    public function getNewMessages()
    {
        // TODO: Implement getNewMessages() method.
    }

    /**
     * Joins a channel.
     *
     * @param Channel $channel the channel to join.
     */
    public function joinChannel(Channel $channel)
    {
        // TODO: Implement joinChannel() method.
    }
}

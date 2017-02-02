<?php

namespace Chaton\Chat;

use Chaton\Model\Channel;
use Chaton\Model\ChatInterface;
use Chaton\Model\Message;
use Chaton\Model\User;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class ConsoleChat implements ChatInterface
{
    private $input;
    private $output;
    private $stdin;
    private $users;

    public function __construct(InputInterface $input, OutputInterface $output)
    {
        $this->input = $input;
        $this->output = $output;
        $this->users = [
            "chaton" => new User($this, "chaton"),
            "user" => new User($this, "user")
        ];
        $this->channels = [
            'default' => new Channel($this, 'default')
        ];
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
        $output->writeln("> Welcome to Chaton :)");

        $this->stdin = fopen('php://stdin', 'r');
        stream_set_blocking($this->stdin, false);
    }

    /**
     * Channel[]
     */
    public function getChannels(): array
    {
        return [
            new Channel($this, 'default'),
        ];
    }

    /**
     * @return User[]
     */
    public function getUsers(Channel $channel): array
    {
        if ($channel->getId() == 'default') {
            return $this->users;
        }
    }

    public function sendMessage(Message $message)
    {
        echo($message);
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
        $input = trim(fgets($this->stdin));

        if (!$input) {
            return [];
        }

        $message = new Message($this, $this->users["user"], $this->channels['default'], $input);

        return [$message];
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

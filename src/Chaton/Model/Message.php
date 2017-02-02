<?php

namespace Chaton\Model;

class Message
{
    /**
     * @var Channel
     */
    private $channel;

    /**
     * @var User
     */
    private $user;

    /**
     * @var string
     */
    private $message;

    private $options;

    /**
     * @var ChatInterface
     */
    private $chat;

    public function __construct(ChatInterface $chat, User $user, Channel $channel, string $message, array $options = array())
    {
        $this->user = $user;
        $this->channel = $channel;
        $this->message = $message;
        $this->options = $options;
        $this->chat = $chat;
    }

    /**
     * @return Channel
     */
    public function getChannel()
    {
        return $this->channel;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }


    public function reply(string $message)
    {
        $msg = new Message($this->chat, $this->user, $this->channel, $message); // todo: pas $this->user
        $this->chat->sendMessage($msg);
    }

    public function __toString()
    {
        return $this->message;
    }
}

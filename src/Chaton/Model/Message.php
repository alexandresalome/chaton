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

    public function __construct(User $user, Channel $channel, string $message)
    {
        $this->user = $user;
        $this->channel = $channel;
        $this->message = $message;
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
}

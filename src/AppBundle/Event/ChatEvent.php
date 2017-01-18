<?php

namespace AppBundle;

use AppBundle\Chat\Message;
use Symfony\Component\EventDispatcher\Event;

class ChatEvent extends Event
{
    /**
     * @var Message
     */
    private $message;

    public function __construct(Message $message)
    {
        $this->message = $message;
    }
}
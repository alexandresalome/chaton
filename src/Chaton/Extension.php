<?php

namespace Chaton;

use Chaton\Model\Message;

class Extension
{
    public function filterInput(Message $message) : Message
    {
        return $message;
    }

    public function handle(Chaton $chaton, Message $message) : bool
    {
        return false;
    }

    public function filterOutput(Message $message)
    {
        return $message;
    }
}

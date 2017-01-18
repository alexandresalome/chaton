<?php

namespace AppBundle\Chat;

interface ChatInterface
{
    /**
     * Channel[]
     */
    public function getChannels() : array;

    /**
     * @return User[]
     */
    public function getUsers(Channel $channel): array;

    public function sendMessage(Message $message);

    public function onMessage(callable $callback);

    public function connect();

    public function tick();
}

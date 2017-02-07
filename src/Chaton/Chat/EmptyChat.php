<?php

namespace Chaton\Chat;

use Chaton\Model\Channel;
use Chaton\Model\ChatInterface;
use Chaton\Model\Message;
use Chaton\Model\User;

class EmptyChat implements ChatInterface
{
    /**
     * {@inheritdoc}
     */
    public function connect()
    {
        // nothing to do
    }

    /**
     * {@inheritdoc}
     */
    public function getNewMessages()
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function sendMessage(Message $message)
    {
        // nothing to do
    }

    /**
     * {@inheritdoc}
     */
    public function getChannels(): array
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function joinChannel(Channel $channel)
    {
        // nothing to do
    }

    /**
     * {@inheritdoc}
     */
    public function getUsers(Channel $channel): array
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function getAllUsers(): array
    {
        return [];
    }
}
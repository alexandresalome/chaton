<?php

namespace Chaton\Model;

/**
 * Information about a user
 */
class User
{
    private $id;
    private $options;

    /**
     * @var ChatInterface
     */
    private $chat;

    public function __construct(ChatInterface $chat, string $id, array $options = [])
    {
        $this->id = $id;
        $this->options = $options;
        $this->chat = $chat;
    }
}

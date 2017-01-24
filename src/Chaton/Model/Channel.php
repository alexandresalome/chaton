<?php

namespace Chaton\Model;

class Channel
{
    private $id;
    private $private;
    private $chat;

    public function __construct(ChatInterface $chat,  $id, bool $private = false)
    {
        $this->id = $id;
        $this->private = $private;
        $this->chat = $chat;
    }

    /**
     * @return ChatInterface
     */
    public function getChat(): ChatInterface
    {
        return $this->chat;
    }

    public function getId()
    {
        return $this->id;
    }

    public function isPrivate()
    {
        return $this->private;
    }
}

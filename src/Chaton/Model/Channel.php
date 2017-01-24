<?php

namespace Chaton\Model;

class Channel
{
    private $id;
    private $private;

    public function __construct(string $id, bool $private = false)
    {
        $this->id = $id;
        $this->private = $private;
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

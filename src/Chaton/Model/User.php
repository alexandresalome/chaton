<?php

namespace Chaton\Model;

/**
 * Information about a user
 */
class User
{
    private $id;
    private $options;

    public function __construct(string $id, array $options = [])
    {
        $this->id = $id;
        $this->options = $options;
    }
}

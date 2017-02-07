<?php

namespace Chaton\Model;

use Chaton\Chat\EmptyChat;

class UserTest extends \PHPUnit_Framework_TestCase
{
    private static $chat;

    public static function setUpBeforeClass()
    {
        self::$chat = new EmptyChat();
    }

    public function testInstanciate()
    {
        $user = new User(self::$chat, "foo", ["bar" => true]);
    }
}

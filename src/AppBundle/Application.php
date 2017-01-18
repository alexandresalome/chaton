<?php

namespace AppBundle;


use AppBundle\Chat\ChatInterface;
use AppBundle\Chat\Message;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class Application
{
    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;

    /**
     * @var ChatInterface
     */
    private $chat;

    public function __construct(ChatInterface $chat, EventDispatcherInterface $dispatcher)
    {
        $this->chat = $chat;
        $this->dispatcher = $dispatcher;
    }

    public function start()
    {
        $this->chat->onMessage(function (Message $message) {
            $this->dispatcher->dispatch('chat.message', new ChatEvent($message));
        });

        $this->chat->connect();
    }

    public function run()
    {
        $this->start();

        while (true) {
            $this->tick();
            usleep(10000);
        }
    }

    public function tick()
    {
        $this->chat->tick();
    }

    /**
     * @return ChatInterface
     */
    public function getChat(): ChatInterface
    {
        return $this->chat;
    }
}

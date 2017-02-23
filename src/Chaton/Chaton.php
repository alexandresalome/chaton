<?php

namespace Chaton;


use Chaton\Extensions\BobMarlex\BobMarlexExtension;
use Chaton\Extensions\Curl\CurlExtension;
use Chaton\Model\ChatInterface;
use Chaton\Model\Message;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\CurlMultiHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Promise\PromiseInterface;
use Pimple\Container;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use React\EventLoop\Factory;
use React\EventLoop\LibEventLoop;
use React\EventLoop\Timer\Timer;
use WyriHaximus\React\GuzzlePsr7\HttpClientAdapter;

class Chaton extends Container
{
    /**
     * @param Extension[] $extensions
     * @param LoggerInterface $logger
     */
    public function __construct(array $extensions = array(), LoggerInterface $logger = null)
    {
        if (empty($extensions)) {
            $extensions = array(
                new BobMarlexExtension(),
                new CurlExtension()
            );
        }

        if (!$logger) {
            $logger = new NullLogger();
        }

        parent::__construct(array(
            'loop' => Factory::create(),
            'logger' => $logger,
            'extensions' => $extensions,
            'chats' => [],
        ));

        foreach ($extensions as $extension) {
            $extension->register($this);
        }

        $this['loop']->addPeriodicTimer(0.1, function () {
            foreach ($this['chats'] as $chat) {
                foreach ($chat->getNewMessages() as $message) {
                    $this->onMessage($message);
                }
            }
        });
    }

    /**
     * Adds a chat.
     *
     * @param ChatInterface $chat
     *
     * @return Chaton for fluid-interface
     */
    public function addChat(ChatInterface $chat) : Chaton
    {
        $chat->connect();

        $chats = $this['chats'];
        $chats[] = $chat;
        $this['chats'] = $chats;

        return $this;
    }

    /**
     * Perpetual execution of the processs.
     */
    public function run()
    {
        while (true) {
            $this['loop']->run();
            usleep(10000);
        }
    }

    /**
     * Execute a single operation.
     */
    public function tick()
    {
        $this['loop']->tick();
    }

    /**
     * Internal routine of message processing.
     *
     * @param Message $message
     */
    private function onMessage(Message $message)
    {
        $this['logger']->debug(sprintf("Received message \"%s\".", $message));

        // apply input filters
        foreach ($this['extensions'] as $extension) {
            $message = $extension->filterInput($message);
        }

        // find an answer
        $handled = false;

        foreach ($this['extensions'] as $extension) {
            if ($extension->handle($this, $message)) {
                $this['logger']->info(sprintf(
                    "Message handled by extension \"%s\".",
                    $message,
                    get_class($extension)
                ));

                $handled = true;
                break;
            }
        }

        if (!$handled) {
            $this['logger']->debug('Message was not handled by any extension.');
        }
    }
}

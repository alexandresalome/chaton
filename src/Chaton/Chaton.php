<?php

namespace Chaton;


use Chaton\Extensions\BobMarlex\BobMarlexExtension;
use Chaton\Extensions\Curl\CurlExtension;
use Chaton\Model\ChatInterface;
use Chaton\Model\Message;
use GuzzleHttp\Client;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use React\EventLoop\Factory;
use React\EventLoop\LibEventLoop;
use React\EventLoop\Timer\Timer;

class Chaton
{
    /**
     * @var Extension[]
     */
    private $extensions;

    /**
     * @var ChatInterface[]
     */
    private $chats = [];

    private $chatCursor = false;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var boolean
     */
    private $started = false;

    /**
     * @var LibEventLoop
     */
    private $loop;

    /**
     * @var Timer<string>
     */
    private $timers = [];

    private $debugTickCounter = 0;

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
            $this->logger = new NullLogger();
        }

        $this->logger = $logger;
        $this->extensions = $extensions;
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
        if ($this->started) {
            throw new \LogicException("Cannot add a chat to chaton: application is already started.");
        }

        $this->logger->debug(sprintf("New chat added: \"%s\".", get_class($chat)));
        $this->chats[] = $chat;
        $this->loop = Factory::create();

        return $this;
    }

    /**
     * Tests if chaton is started.
     *
     * @return bool
     */
    public function isStarted()
    {
        return $this->started;
    }

    /**
     * Starts the execution of chaton.
     */
    public function start()
    {
        if ($this->started) {
            return;
        }

        $this->logger->info("chaton is starting");

        // connect to every chat
        foreach ($this->chats as $chat) {
            $this->logger->debug(sprintf("Connecting to chat \"%s\"", get_class($chat)));
            $chat->connect();
        }

        $this->logger->info("chaton started");

        $this->started = true;
    }

    /**
     * Perpetual execution of the processs.
     */
    public function run()
    {
        if (!$this->started) {
            $this->start();
        }

        while (true) {
            $this->tick();
            usleep(10000);
        }
    }

    /**
     * Execute a single operation.
     */
    public function tick()
    {
        $this->loop->tick();

        $this->debugTickCounter = ($this->debugTickCounter + 1) % 100;
        if ($this->debugTickCounter == 0) {
            $this->logger->debug("1000 ticks executed");
        }

        if ($this->chatCursor === false && count($this->chats) !== 0) {
            $this->chatCursor = reset($this->chats);
        }

        if ($this->chatCursor === false) {
            return;
        }

        $messages = $this->chatCursor->getNewMessages();
        foreach ($messages as $message) {
            $this->onMessage($message);
        }

        $this->chatCursor = next($this->chats);
    }

    /**
     * Internal routine of message processing.
     *
     * @param Message $message
     */
    private function onMessage(Message $message)
    {
        $this->logger->debug(sprintf("Received message \"%s\".", $message));

        // apply input filters
        foreach ($this->extensions as $extension) {
            $message = $extension->filterInput($message);
        }

        // find an answer
        $handled = false;

        foreach ($this->extensions as $extension) {
            if ($extension->handle($this, $message)) {
                $this->logger->info(sprintf(
                    "Message handled by extension \"%s\".",
                    $message,
                    get_class($extension)
                ));

                $handled = true;
                break;
            }
        }

        if (!$handled) {
            $this->logger->debug('Message was not handled by any extension.');
        }
    }

    public function sendMessage($statusCode)
    {

    }

    public function httpGet(string $url, callable $callback)
    {
        $id = md5(uniqid().microtime(true));

        $this->logger->debug('HTTP GET '.$url);

        $client = new Client();
        $promise = $client->getAsync($url);
        $promise->then($callback);

        $this->timers[$id] = $this->loop->addPeriodicTimer(1, function () use ($promise, $id) {
            echo "loop\n";
            $state = $promise->getState();
            echo $state."\n";
            if ($state === "looool") {
                $timer = $this->timers[$id];
                $timer->cancel();
                unset($this->timers[$id]);
            }
        });
    }
}

<?php

namespace Chaton;


use Chaton\Extensions\BobMarlex\BobMarlexExtension;
use Chaton\Model\ChatInterface;
use Chaton\Model\Message;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use React\EventLoop\Factory;
use React\EventLoop\LibEventLoop;

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
     * @param Extension[] $extensions
     * @param LoggerInterface $logger
     */
    public function __construct(array $extensions = array(), LoggerInterface $logger = null)
    {
        if (empty($extensions)) {
            $extensions = array(
                new BobMarlexExtension()
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

        $this->loop->run();
    }

    /**
     * Execute a single operation.
     */
    public function tick()
    {
        $this->loop->tick();

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
            $new = $extension->filterInput($message);
            if (!$new instanceof Message) {
                $this->logger->info(sprintf(
                    "Message skipped by extension \"%s\".",
                    $message,
                    get_class($extension)
                ));

                return;
            }
            $message = $new;
        }

        // find an answer
        foreach ($this->extensions as $extension) {
            if ($extension->handle($message)) {
                $this->logger->info(sprintf(
                    "Message handled by extension \"%s\".",
                    $message,
                    get_class($extension)
                ));

                break;
            }
        }
    }

    public function sendMessage($statusCode)
    {

    }
}

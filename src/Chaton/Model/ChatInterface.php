<?php

namespace Chaton\Model;

interface ChatInterface
{
    /**
     * Connects to the chat
     */
    public function connect();

    /**
     * Returns list of received messages.
     *
     * @return Message[]
     */
    public function getNewMessages();

    /**
     * Sends a message.
     *
     * @param Message $message a Message object
     */
    public function sendMessage(Message $message);

    /**
     * Returns list of available channels.
     *
     * @return Channel[] an array of Channel objects
     */
    public function getChannels() : array;

    /**
     * Joins a channel.
     *
     * @param Channel $channel the channel to join.
     */
    public function joinChannel(Channel $channel);

    /**
     * Returns list of users present on a channel.
     *
     * @param Channel $channel
     *
     * @return User[]
     */
    public function getUsers(Channel $channel): array;

    /**
     * Returns list of users present on a channel.
     *
     * @return User[]
     */
    public function getAllUsers(): array;
}

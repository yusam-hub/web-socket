<?php

namespace YusamHub\WebSocket\WsClient\OutgoingMessages;

use YusamHub\WebSocket\Interfaces\WsClient\WebSocketClientInterface;
use YusamHub\WebSocket\Interfaces\WsClient\WebSocketClientOutgoingMessageInterface;

abstract class BaseMessageOutgoingMessage implements WebSocketClientOutgoingMessageInterface
{
    protected WebSocketClientInterface $wsClient;

    /**
     * @param WebSocketClientInterface $wsClient
     * @return void
     */
    public function setWebSocketClient(WebSocketClientInterface $wsClient): void
    {
        $this->wsClient = $wsClient;
    }

    /**
     * @param \Ratchet\Client\WebSocket $conn
     * @return void
     */
    public function execute(\Ratchet\Client\WebSocket $conn): void
    {

    }
}
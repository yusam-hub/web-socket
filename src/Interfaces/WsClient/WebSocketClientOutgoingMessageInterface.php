<?php

namespace YusamHub\WebSocket\Interfaces\WsClient;

interface WebSocketClientOutgoingMessageInterface
{
    public function setWebSocketClient(WebSocketClientInterface $wsClient): void;
    public function execute(\Ratchet\Client\WebSocket $conn): void;
}

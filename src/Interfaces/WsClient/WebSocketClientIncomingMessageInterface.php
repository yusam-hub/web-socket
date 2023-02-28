<?php

namespace YusamHub\WebSocket\Interfaces\WsClient;

use YusamHub\WebSocket\Interfaces\WebSocketIncomingMessageInterface;

interface WebSocketClientIncomingMessageInterface extends WebSocketIncomingMessageInterface
{
    public function setWebSocketClient(WebSocketClientInterface $wsClient): void;
    public function setWebSocketClientConnection(\Ratchet\Client\WebSocket $conn): void;
}

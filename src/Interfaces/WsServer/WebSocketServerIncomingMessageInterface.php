<?php

namespace YusamHub\WebSocket\Interfaces\WsServer;

use YusamHub\WebSocket\Interfaces\WebSocketConnectionInterface;
use YusamHub\WebSocket\Interfaces\WebSocketIncomingMessageInterface;

interface WebSocketServerIncomingMessageInterface extends WebSocketIncomingMessageInterface
{
    public function setWebSocketConnection(WebSocketConnectionInterface $wsConnection): void;
}

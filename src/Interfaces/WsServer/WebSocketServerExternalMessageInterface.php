<?php

namespace YusamHub\WebSocket\Interfaces\WsServer;

use YusamHub\WebSocket\Interfaces\WebSocketIncomingMessageInterface;

interface WebSocketServerExternalMessageInterface extends WebSocketIncomingMessageInterface
{
    public function setWebSocketServer(WebSocketServerInterface $wsServer): void;
}

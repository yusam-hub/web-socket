<?php

namespace YusamHub\WebSocket\Interfaces\WsClient;

use YusamHub\WebSocket\Interfaces\WebSocketConfigInterface;
use YusamHub\WebSocket\Interfaces\WebSocketOutputInterface;

interface WebSocketClientInterface
{
    public function getWebSocketConfig(): WebSocketConfigInterface;
    public function getWebSocketOutput(): WebSocketOutputInterface;
}
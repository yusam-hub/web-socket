<?php

namespace YusamHub\WebSocket\Interfaces;

use YusamHub\WebSocket\Interfaces\WsServer\WebSocketServerInterface;

/**
 * Interface WebSocketConnectionInterface
 * @package YusamHub\WebSocket\Interfaces
 */
interface WebSocketConnectionInterface
{
    public function getWebSocketServer(): WebSocketServerInterface;
    public function getConnection(): \Ratchet\ConnectionInterface;
    public function getConnectionResourceId(): int;
    public function getConnectionUriPath(): string;
    public function setConnectionAttribute(string $key, $value): void;
    public function getConnectionAttribute(string $key, $default = null);
    public function getConnectionUriQuery(): array;
    public function getConnectionUriQueryByKey(string $key, ?string $default = null): ?string;
    public function sendStringMessage(string $stringMessage): void;
    public function sendMessage(array $message): void;
    public function close(): void;
}
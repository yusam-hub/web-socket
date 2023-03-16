<?php

namespace YusamHub\WebSocket\Interfaces\WsServer;

use YusamHub\WebSocket\Interfaces\WebSocketConnectionInterface;
use YusamHub\WebSocket\Interfaces\WebSocketDaemonInterface;
use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;

/**
 * Interface WebSocketServerInterface
 * @package YusamHub\WebSocket\Interfaces
 */
interface WebSocketServerInterface extends MessageComponentInterface
{
    public function registerIncomingMessagesClass(array $incomingMessagesClass): void;
    public function registerExternalMessagesClass(array $externalMessagesClass): void;
    public function getProperty(string $propertyName, $default = null);
    public function wsConnection(ConnectionInterface $conn): WebSocketConnectionInterface;
    public function getWebSocketDaemon(): WebSocketDaemonInterface;
    public function countConnections(): int;
    public function broadcastStringMessageByAttribute(string $msg, string $attributeKey, $attributeValue): void;
    public function broadcastMessageByAttribute(array $arrayMsg, string $attributeKey, $attributeValue): void;
    public function broadcastStringMessageByAttributes(string $msg, array $attributeKeyValuePair): void;
    public function broadcastMessageByAttributes(array $arrayMsg, array $attributeKeyValuePair): void;
    public function broadcastStringMessage(string $msg): void;
    public function broadcastMessage(array $arrayMsg): void;
    public function isShouldQuit(): bool;
    public function setShouldQuit(bool $shouldQuit): void;
    public function onExternalMessage(string $stringMessage): void;
}
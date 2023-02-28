<?php

namespace YusamHub\WebSocket\Interfaces;

/**
 * Interface WebSocketDaemonInterface
 * @package YusamHub\WebSocket\Interfaces
 */
interface WebSocketDaemonInterface
{
    public function isDebugging(): bool;
    public function setDebugging(bool $value): void;
    public function getWebSocketConfig(): WebSocketConfigInterface;
    public function getWebSocketOutput(): WebSocketOutputInterface;
    public function run(): void;
}
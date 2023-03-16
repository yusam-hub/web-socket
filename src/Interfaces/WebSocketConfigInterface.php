<?php

namespace YusamHub\WebSocket\Interfaces;

/**
 * Interface WebSocketConfigInterface
 * @package YusamHub\WebSocket\Interfaces
 */
interface WebSocketConfigInterface
{
    public function getBindAddress(): string;
    public function getBindPort(): string;
    public function getBindPullAddress(): string;
    public function getBindPullPort(): string;
}
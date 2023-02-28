<?php

namespace YusamHub\WebSocket\Interfaces;

/**
 * Interface WebSocketOutputInterface
 * @package YusamHub\WebSocket\Interfaces
 */
interface WebSocketOutputInterface
{
    const ECHO_TYPE_INFO = 'INFO';
    const ECHO_TYPE_DEBUG = 'DEBUG';
    const ECHO_TYPE_EXCEPTION = 'EXCEPTION';

    public function echoInfo(string $message, array $context = []): void;
    public function echoDebug(string $message, array $context = []): void;
    public function echoException(\Exception $e, array $context = [], string $message = ''): void;
    public function echoDebugMemoryUsage(): void;
}
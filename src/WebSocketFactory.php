<?php

namespace YusamHub\WebSocket;

use YusamHub\WebSocket\Interfaces\WebSocketConfigInterface;
use YusamHub\WebSocket\Interfaces\WebSocketDaemonInterface;
use YusamHub\WebSocket\Interfaces\WebSocketOutputInterface;
use YusamHub\WebSocket\Interfaces\WsServer\WebSocketServerInterface;

/**
 * Class WebSocketFactory
 * @package YusamHub\WebSocket
 */
class WebSocketFactory
{
    protected static string $webSocketDaemonClass = \YusamHub\WebSocket\WebSocketDaemon::class;
    protected static string $webSocketConfigClass = \YusamHub\WebSocket\WebSocketConfig::class;
    protected static string $webSocketOutputClass = \YusamHub\WebSocket\WebSocketOutput::class;
    protected static string $webSocketServerClass = \YusamHub\WebSocket\WebSocketServer::class;

    /**
     * @param string $webSocketDaemonClass
     */
    public static function setWebSocketDaemonClass(string $webSocketDaemonClass): void
    {
        static::$webSocketDaemonClass = $webSocketDaemonClass;
    }

    /**
     * @param string $webSocketConfigClass
     */
    public static function setWebSocketConfigClass(string $webSocketConfigClass): void
    {
        static::$webSocketConfigClass = $webSocketConfigClass;
    }

    /**
     * @param string $webSocketOutputClass
     */
    public static function setWebSocketOutputClass(string $webSocketOutputClass): void
    {
        static::$webSocketOutputClass = $webSocketOutputClass;
    }

    /**
     * @param string $webSocketServerClass
     */
    public static function setWebSocketServerClass(string $webSocketServerClass): void
    {
        static::$webSocketServerClass = $webSocketServerClass;
    }

    /**
     * @param array $config
     * @return WebSocketConfigInterface
     */
    public static function newConfig(array $config = []): WebSocketConfigInterface
    {
        return new static::$webSocketConfigClass($config);
    }

    /**
     * @param \Closure|null $outputCallback - function(string $type, string $message, array $context = []){}
     * @return WebSocketOutputInterface
     */
    public static function newOutput(?\Closure $outputCallback = null): WebSocketOutputInterface
    {
        return new static::$webSocketOutputClass($outputCallback);
    }

    /**
     * @param WebSocketConfigInterface $webSocketConfig
     * @param WebSocketOutputInterface $webSocketOutput
     * @return WebSocketDaemonInterface
     */
    public static function newDaemon(
        WebSocketConfigInterface $webSocketConfig,
        WebSocketOutputInterface $webSocketOutput
    ): WebSocketDaemonInterface
    {
        return new static::$webSocketDaemonClass($webSocketConfig, $webSocketOutput);
    }

    /**
     * @param WebSocketDaemonInterface $webSocketDaemon
     * @return WebSocketServerInterface
     */
    public static function newServer(
        WebSocketDaemonInterface $webSocketDaemon
    ): WebSocketServerInterface
    {
        return new static::$webSocketServerClass($webSocketDaemon);
    }
}
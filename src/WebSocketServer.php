<?php

namespace YusamHub\WebSocket;

use YusamHub\WebSocket\Interfaces\WebSocketConnectionInterface;
use YusamHub\WebSocket\Interfaces\WebSocketDaemonInterface;
use YusamHub\WebSocket\Interfaces\WsServer\WebSocketServerExternalMessageInterface;
use YusamHub\WebSocket\Interfaces\WsServer\WebSocketServerIncomingMessageInterface;
use YusamHub\WebSocket\Interfaces\WsServer\WebSocketServerInterface;
use Ratchet\ConnectionInterface;

/**
 * Class WebSocketServer
 * @package YusamHub\WebSocket
 */
class WebSocketServer implements WebSocketServerInterface
{
    /**
     * @var \SplObjectStorage
     */
    protected \SplObjectStorage $connections;

    /**
     * @var bool
     */
    protected bool $shouldQuit = false;

    /**
     * @var array
     */
    protected array $incomingMessagesClass = [
        \YusamHub\WebSocket\WsServer\IncomingMessages\PingPongIncomingMessage::class,
    ];

    /**
     * @var array
     */
    protected array $externalMessagesClass = [
        \YusamHub\WebSocket\WsServer\ExternalMessages\PingPongExternalMessage::class,
    ];

    /**
     * @var WebSocketDaemonInterface
     */
    protected WebSocketDaemonInterface $webSocketDaemon;

    /**
     * WebSocketServer constructor.
     * @param WebSocketDaemonInterface $webSocketDaemon
     */
    public function __construct(WebSocketDaemonInterface $webSocketDaemon)
    {
        $this->connections = new \SplObjectStorage();
        $this->webSocketDaemon = $webSocketDaemon;
        $this->webSocketDaemon->getWebSocketOutput()->echoInfo('INIT', [get_class($this)]);
    }

    /**
     * @param ConnectionInterface $conn
     * @return WebSocketConnectionInterface
     */
    public function wsConnection(ConnectionInterface $conn): WebSocketConnectionInterface
    {
        return new WebSocketConnection($this, $conn);
    }

    /**
     * @param ConnectionInterface $conn
     */
    public function onOpen(ConnectionInterface $conn)
    {
        if ($this->shouldQuit) {
            $conn->close();
            return;
        }
        $this->connections->attach($conn);
        $this->connectionAttach($this->wsConnection($conn));
    }

    /**
     * @param WebSocketConnectionInterface $wsConnection
     */
    protected function connectionAttach(WebSocketConnectionInterface $wsConnection): void
    {
        $this->webSocketDaemon->getWebSocketOutput()->echoInfo("ATTACH", [
            'rid' => $wsConnection->getConnectionResourceId(),
            'path' => $wsConnection->getConnectionUriPath(),
            'query' => $wsConnection->getConnectionUriQuery(),
        ]);
    }

    /**
     * @param ConnectionInterface $conn
     */
    public function onClose(ConnectionInterface $conn)
    {
        $this->connectionDetach($this->wsConnection($conn));
        $this->connections->detach($conn);
    }

    /**
     * @param WebSocketConnectionInterface $wsConnection
     */
    protected function connectionDetach(WebSocketConnectionInterface $wsConnection): void
    {
        $this->webSocketDaemon->getWebSocketOutput()->echoInfo("DETACH", [
            'rid' => $wsConnection->getConnectionResourceId(),
        ]);
    }

    /**
     * @param ConnectionInterface $conn
     * @param \Exception $e
     */
    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        if ($this->shouldQuit) {
            return;
        }
        $this->connectionOnError($this->wsConnection($conn), $e);
    }

    /**
     * @param WebSocketConnectionInterface $wsConnection
     * @param \Exception $e
     */
    protected function connectionOnError(WebSocketConnectionInterface $wsConnection, \Exception $e): void
    {
        $this->webSocketDaemon->getWebSocketOutput()->echoException($e, [
            'rid' => $wsConnection->getConnectionResourceId(),
        ]);
        $wsConnection->close();
    }

    /**
     * @param ConnectionInterface $from
     * @param string $msg
     */
    public function onMessage(ConnectionInterface $from, $msg)
    {
        if ($this->shouldQuit) {
            return;
        }
        $arrayMsg = (array) @json_decode($msg, true);
        $this->connectionOnMessage($this->wsConnection($from), $msg, $arrayMsg);
    }

    /**
     * @param WebSocketConnectionInterface $wsConnection
     * @param string $msg
     * @param array $arrayMsg
     */
    protected function connectionOnMessage(
        WebSocketConnectionInterface $wsConnection,
        string $msg,
        array $arrayMsg
    ): void
    {
        $this->webSocketDaemon->getWebSocketOutput()->echoDebugMemoryUsage();

        $this->webSocketDaemon->getWebSocketOutput()->echoInfo("RECV", [
            'rid' => $wsConnection->getConnectionResourceId(),
            'msg' => !empty($arrayMsg) ? $arrayMsg : $msg
        ]);

        foreach($this->incomingMessagesClass as $objClass) {
            $obj = new $objClass();
            if ($obj instanceof WebSocketServerIncomingMessageInterface) {
                $obj->setWebSocketConnection($wsConnection);
                $obj->setWebSocketMessage($msg, $arrayMsg);
                if ($obj->validate()) {
                    $obj->execute();
                    return;
                }
            }
        }
    }

    /**
     * @param string $stringMessage
     */
    public function onExternalMessage(string $stringMessage): void
    {
        if ($this->shouldQuit) {
            return;
        }
        $arrayMsg = (array) @json_decode($stringMessage, true);
        $this->connectionOnExternalMessage($stringMessage, $arrayMsg);
    }

    /**
     * @param string $stringMessage
     * @param array $arrayMsg
     */
    protected function connectionOnExternalMessage(string $stringMessage, array $arrayMsg): void
    {
        $this->webSocketDaemon->getWebSocketOutput()->echoDebugMemoryUsage();

        $this->webSocketDaemon->getWebSocketOutput()->echoInfo("EXTERNAL", [
            'msg' => !empty($arrayMsg) ? $arrayMsg : $stringMessage
        ]);

        foreach($this->externalMessagesClass as $objClass) {
            $obj = new $objClass();
            if ($obj instanceof WebSocketServerExternalMessageInterface) {
                $obj->setWebSocketServer($this);
                $obj->setWebSocketMessage($stringMessage, $arrayMsg);
                if ($obj->validate()) {
                    $obj->execute();
                    return;
                }
            }
        }
    }

    /**
     * @return WebSocketDaemonInterface
     */
    public function getWebSocketDaemon(): WebSocketDaemonInterface
    {
        return $this->webSocketDaemon;
    }

    /**
     * @return bool
     */
    public function isShouldQuit(): bool
    {
        return $this->shouldQuit;
    }

    /**
     * @param bool $shouldQuit
     */
    public function setShouldQuit(bool $shouldQuit): void
    {
        if ($this->shouldQuit !== $shouldQuit) {
            $this->shouldQuit = $shouldQuit;
            $this->signalShouldQuitAll();
        }
    }

    /**
     * @return int
     */
    public function countConnections(): int
    {
        return $this->connections->count();
    }

    /**
     * @param string $msg
     * @param string $attributeKey
     * @param mixed $attributeValue
     * @return void
     */
    public function broadcastStringMessageByAttribute(string $msg, string $attributeKey, $attributeValue): void
    {
        if ($this->shouldQuit || is_null($attributeValue)) {
            return;
        }

        /**
         * @var ConnectionInterface $conn
         */
        foreach($this->connections as $conn) {

            if ($this->shouldQuit) {
                return;
            }

            $wsConnection = $this->wsConnection($conn);
            $value = $wsConnection->getConnectionAttribute($attributeKey);
            if (
                (is_array($attributeValue) && in_array($value, $attributeValue))
                ||
                (!is_array($attributeValue) && strval($value) === strval($attributeValue))
            ) {
                $wsConnection->sendStringMessage($msg);
            }
        }
    }

    /**
     * @param array $arrayMsg
     * @param string $attributeKey
     * @param mixed $attributeValue
     * @return void
     */
    public function broadcastMessageByAttribute(array $arrayMsg, string $attributeKey, $attributeValue): void
    {
        if ($this->shouldQuit || is_null($attributeValue)) {
            return;
        }

        /**
         * @var ConnectionInterface $conn
         */
        foreach($this->connections as $conn) {

            if ($this->shouldQuit) {
                return;
            }

            $wsConnection = $this->wsConnection($conn);
            $value = $wsConnection->getConnectionAttribute($attributeKey);
            if (
                (is_array($attributeValue) && in_array($value, $attributeValue))
                ||
                (!is_array($attributeValue) && strval($value) === strval($attributeValue))
            ) {
                $wsConnection->sendMessage($arrayMsg);
            }
        }
    }

    /**
     * @param string $msg
     * @param array $attributeKeyValuePair
     * @return void
     */
    public function broadcastStringMessageByAttributes(string $msg, array $attributeKeyValuePair): void
    {
        if ($this->shouldQuit) {
            return;
        }
        /**
         * @var ConnectionInterface $conn
         */
        foreach($this->connections as $conn) {

            if ($this->shouldQuit) {
                return;
            }

            $wsConnection = $this->wsConnection($conn);
            $f=0;
            foreach($attributeKeyValuePair as $attributeKey => $attributeValue) {
                if (!is_null($attributeValue)) {
                    $value = $wsConnection->getConnectionAttribute($attributeKey);
                    if (
                        (is_array($attributeValue) && in_array($value, $attributeValue))
                        ||
                        (!is_array($attributeValue) && strval($value) === strval($attributeValue))
                    ) {
                        $f++;
                    }
                }
            }
            if ($f === count($attributeKeyValuePair)) {
                $wsConnection->sendStringMessage($msg);
            }
        }
    }

    public function broadcastMessageByAttributes(array $arrayMsg, array $attributeKeyValuePair): void
    {
        if ($this->shouldQuit) {
            return;
        }
        /**
         * @var ConnectionInterface $conn
         */
        foreach($this->connections as $conn) {

            if ($this->shouldQuit) {
                return;
            }

            $wsConnection = $this->wsConnection($conn);
            $f=0;
            foreach($attributeKeyValuePair as $attributeKey => $attributeValue) {
                if (!is_null($attributeValue)) {
                    $value = $wsConnection->getConnectionAttribute($attributeKey);
                    if (
                        (is_array($attributeValue) && in_array($value, $attributeValue))
                        ||
                        (!is_array($attributeValue) && strval($value) === strval($attributeValue))
                    ) {
                        $f++;
                    }
                }
            }
            if ($f === count($attributeKeyValuePair)) {
                $wsConnection->sendMessage($arrayMsg);
            }
        }
    }

    /**
     * @param string $msg
     * @return void
     */
    public function broadcastStringMessage(string $msg): void
    {
        if ($this->shouldQuit) {
            return;
        }

        /**
         * @var ConnectionInterface $conn
         */
        foreach($this->connections as $conn) {
            if ($this->shouldQuit) {
                return;
            }
            $wsConnection = $this->wsConnection($conn);
            $wsConnection->sendStringMessage($msg);
        }
    }

    /**
     * @param array $arrayMsg
     * @return void
     */
    public function broadcastMessage(array $arrayMsg): void
    {
        if ($this->shouldQuit) {
            return;
        }

        /**
         * @var ConnectionInterface $conn
         */
        foreach($this->connections as $conn) {
            if ($this->shouldQuit) {
                return;
            }
            $wsConnection = $this->wsConnection($conn);
            $wsConnection->sendMessage($arrayMsg);
        }
    }

    /**
     *
     */
    protected function signalShouldQuitAll(): void
    {
        $this->webSocketDaemon->getWebSocketOutput()->echoInfo("signalShouldQuitAll", [
            'countConnections' => $this->countConnections(),
        ]);

        /**
         * @var ConnectionInterface $conn
         */
        foreach($this->connections as $conn) {
            $this->connectionSignalShouldQuit($this->wsConnection($conn));
        }
    }

    /**
     * !!! Если переопределяем метод, то в нем лучше не посылать сообщений клиенту, так как клиент в массиве у нас и уже может быть отключен к этому времени
     *     Здесь лучше производить какие-то действия на сервере в БД, например проставить признак или еще что-то
     *
     * @param WebSocketConnectionInterface $wsConnection
     */
    protected function connectionSignalShouldQuit(WebSocketConnectionInterface $wsConnection): void
    {
        $this->webSocketDaemon->getWebSocketOutput()->echoInfo("connectionSignalShouldQuit", [
            'rid' => $wsConnection->getConnectionResourceId(),
        ]);
        $wsConnection->close();
    }

    /**
     * @param string $propertyName
     * @param null $default
     * @return mixed
     */
    public function getProperty(string $propertyName, $default = null)
    {
        if (property_exists($this, $propertyName)) {
            return $this->{$propertyName};
        }
        return $default;
    }
}
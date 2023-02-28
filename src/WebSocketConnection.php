<?php

namespace YusamHub\WebSocket;

use YusamHub\WebSocket\Interfaces\WebSocketConnectionInterface;
use YusamHub\WebSocket\Interfaces\WsServer\WebSocketServerInterface;

/**
 * Class WebSocketConnection
 * @package YusamHub\WebSocket
 */
class WebSocketConnection implements WebSocketConnectionInterface
{
    /**
     * @var WebSocketServerInterface
     */
    protected WebSocketServerInterface $webSocketServer;


    /**
     * @var \Ratchet\ConnectionInterface
     */
    protected \Ratchet\ConnectionInterface $connection;

    /**
     * WebSocketConnection constructor.
     * @param WebSocketServerInterface $webSocketServer
     * @param \Ratchet\ConnectionInterface $connection
     */
    public function __construct(WebSocketServerInterface $webSocketServer,\Ratchet\ConnectionInterface $connection)
    {
        $this->webSocketServer = $webSocketServer;
        $this->connection = $connection;
    }

    /**
     * @return WebSocketServerInterface
     */
    public function getWebSocketServer(): WebSocketServerInterface
    {
        return $this->webSocketServer;
    }


    /**
     * @return \Ratchet\ConnectionInterface
     */
    public function getConnection(): \Ratchet\ConnectionInterface
    {
        return $this->connection;
    }

    /**
     * @return int
     */
    public function getConnectionResourceId(): int
    {
        return $this->connection->resourceId;
    }

    /**
     * @return string
     */
    public function getConnectionUriPath(): string
    {
        return $this->connection->httpRequest->getUri()->getPath();
    }

    /**
     * @param string $key
     * @param $value
     */
    public function setConnectionAttribute(string $key, $value): void
    {
        $this->connection->{$key} = $value;
    }

    /**
     * @param string $key
     * @param null $default
     * @return mixed
     */
    public function getConnectionAttribute(string $key, $default = null): mixed
    {
        if (isset($this->connection->{$key})) {
            return $this->connection->{$key};
        }
        return $default;
    }

    /**
     * @return array
     */
    public function getConnectionUriQuery(): array
    {
        $out = [];

        $query = $this->connection->httpRequest->getUri()->getQuery();

        parse_str($query, $out);

        return $out;
    }

    /**
     * @param string $key
     * @param string|null $default
     * @return string|null
     */
    public function getConnectionUriQueryByKey(string $key, ?string $default = null): ?string
    {
        $out = $this->getConnectionUriQuery();

        if (isset($out[$key])) {
            return $out[$key];
        }

        return $default;
    }

    /**
     * @param string $stringMessage
     */
    public function sendStringMessage(string $stringMessage): void
    {
        $this->webSocketServer->getWebSocketDaemon()->getWebSocketOutput()->echoInfo("SEND", [
            'rid' => $this->connection->resourceId,
            'msg' => $stringMessage
        ]);
        $this->connection->send($stringMessage);
    }

    /**
     * @param array $message
     * @param int $jsonFlags
     */
    public function sendMessage(array $message, int $jsonFlags = JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES): void
    {
        $this->webSocketServer->getWebSocketDaemon()->getWebSocketOutput()->echoInfo("SEND", [
            'rid' => $this->connection->resourceId,
            'msg' => $message
        ]);
        $this->connection->send(json_encode($message, $jsonFlags));
    }

    /**
     * @return void
     */
    public function close(): void
    {
        $this->connection->close();
    }

}
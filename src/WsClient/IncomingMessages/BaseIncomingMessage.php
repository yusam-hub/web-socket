<?php

namespace YusamHub\WebSocket\WsClient\IncomingMessages;

use YusamHub\WebSocket\Interfaces\WsClient\WebSocketClientIncomingMessageInterface;
use YusamHub\WebSocket\Interfaces\WsClient\WebSocketClientInterface;

abstract class BaseIncomingMessage implements WebSocketClientIncomingMessageInterface
{
    protected WebSocketClientInterface $wsClient;
    protected \Ratchet\Client\WebSocket $conn;
    protected string $msg;
    protected array $arrayMsg;

    /**
     * @param WebSocketClientInterface $wsClient
     * @return void
     */
    public function setWebSocketClient(WebSocketClientInterface $wsClient): void
    {
        $this->wsClient = $wsClient;
    }

    /**
     * @param \Ratchet\Client\WebSocket $conn
     * @return void
     */
    public function setWebSocketClientConnection(\Ratchet\Client\WebSocket $conn): void
    {
        $this->conn = $conn;
    }

    /**
     * @param string $msg
     * @param array $arrayMsg
     * @return void
     */
    public function setWebSocketMessage(string $msg, array $arrayMsg): void
    {
        $this->msg = $msg;
        $this->arrayMsg = $arrayMsg;
    }

    /**
     * @return bool
     */
    public function validate(): bool
    {
        return true;
    }

    /**
     * @return void
     */
    public function execute(): void
    {

    }
}
<?php

namespace YusamHub\WebSocket\WsServer\IncomingMessages;

use YusamHub\WebSocket\Interfaces\WebSocketConnectionInterface;
use YusamHub\WebSocket\Interfaces\WsServer\WebSocketServerIncomingMessageInterface;

abstract class BaseIncomingMessage implements WebSocketServerIncomingMessageInterface
{
    /**
     * @var WebSocketConnectionInterface
     */
    protected WebSocketConnectionInterface $wsConnection;

    /**
     * @var string
     */
    protected string $msg;

    /**
     * @var array
     */
    protected array $arrayMsg;

    /**
     * @param WebSocketConnectionInterface $wsConnection
     * @return void
     */
    public function setWebSocketConnection(WebSocketConnectionInterface $wsConnection): void
    {
        $this->wsConnection = $wsConnection;
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
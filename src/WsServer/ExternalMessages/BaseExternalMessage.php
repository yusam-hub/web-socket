<?php

namespace YusamHub\WebSocket\WsServer\ExternalMessages;

use YusamHub\WebSocket\Interfaces\WsServer\WebSocketServerExternalMessageInterface;
use YusamHub\WebSocket\Interfaces\WsServer\WebSocketServerInterface;

abstract class BaseExternalMessage implements WebSocketServerExternalMessageInterface
{
    /**
     * @var WebSocketServerInterface
     */
    protected WebSocketServerInterface $wsServer;

    /**
     * @var string
     */
    protected string $msg;

    /**
     * @var array
     */
    protected array $arrayMsg;

    /**
     * @param WebSocketServerInterface $wsServer
     * @return void
     */
    public function setWebSocketServer(WebSocketServerInterface $wsServer): void
    {
        $this->wsServer = $wsServer;
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
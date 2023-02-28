<?php

namespace YusamHub\WebSocket\WsClient\IncomingMessages;

class PongIncomingMessage extends BaseIncomingMessage
{
    /**
     * @return bool
     */
    public function validate(): bool
    {
        return $this->msg === 'PONG';
    }

    /**
     * @return void
     */
    public function execute(): void
    {
        $this->wsClient->getWebSocketOutput()->echoInfo("RECV PONG");
    }
}
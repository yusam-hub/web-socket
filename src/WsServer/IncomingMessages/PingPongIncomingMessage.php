<?php

namespace YusamHub\WebSocket\WsServer\IncomingMessages;

class PingPongIncomingMessage extends BaseIncomingMessage
{
    /**
     * @return bool
     */
    public function validate(): bool
    {
        return $this->msg === 'PING';
    }

    /**
     * @return void
     */
    public function execute(): void
    {
        $this->wsConnection->sendStringMessage('PONG');
    }
}
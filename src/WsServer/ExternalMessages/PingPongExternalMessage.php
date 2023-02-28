<?php

namespace YusamHub\WebSocket\WsServer\ExternalMessages;

class PingPongExternalMessage extends BaseExternalMessage
{
    /**
     * @return bool
     */
    public function validate(): bool
    {
        return $this->msg === 'EXTERNAL PING';
    }

    /**
     * @return void
     */
    public function execute(): void
    {
        $this->wsServer->broadcastStringMessage('EXTERNAL PONG');
    }
}
<?php

namespace YusamHub\WebSocket\Interfaces;

interface WebSocketIncomingMessageInterface
{
    public function setWebSocketMessage(string $msg, array $arrayMsg): void;
    public function validate(): bool;
    public function execute(): void;
}

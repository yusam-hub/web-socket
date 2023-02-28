<?php

namespace YusamHub\WebSocket;

use YusamHub\WebSocket\Interfaces\WebSocketOutputInterface;

/**
 * Class WebSocketOutput
 */
class WebSocketOutput implements WebSocketOutputInterface
{
    /**
     * @var \Closure|null
     */
    protected ?\Closure $outputCallback;

    /**
     * WebSocketOutput constructor.
     * @param ?\Closure $outputCallback
     */
    public function __construct(?\Closure $outputCallback = null)
    {
        $this->outputCallback = $outputCallback;
    }

    /**
     * @param string $type
     * @param string $message
     * @param array $context
     */
    protected function echoCustom(string $type, string $message, array $context = [])
    {
        if (is_callable($this->outputCallback)) {
            $outputCallback = $this->outputCallback;
            $outputCallback($type, $message, $context);
        } else {
            echo sprintf('[%s][%s][%s]: %s %s', $type, getmypid(), date("Y-m-d H:i:s") , $message , (!empty($context) ? json_encode($context, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) : "") . PHP_EOL);
        }
    }

    /**
     * @param string $message
     * @param array $context
     */
    public function echoInfo(string $message, array $context = []): void
    {
        $this->echoCustom(self::ECHO_TYPE_INFO, $message, $context);
    }

    /**
     * @param string $message
     * @param array $context
     */
    public function echoDebug(string $message, array $context = []): void
    {
        $this->echoCustom(self::ECHO_TYPE_DEBUG, $message, $context);
    }

    /**
     * @param \Exception $e
     * @param array $context
     * @param string $message
     */
    public function echoException(\Exception $e, array $context = [], string $message = ''): void
    {
        if (empty($message)) {
            $message = $e->getMessage();
        }
        $context['exception'] = [
            'message' => $e->getMessage(),
            'code' => $e->getCode(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'class' => get_class($e),
        ];
        $this->echoCustom(self::ECHO_TYPE_EXCEPTION, $message, $context);
    }

    /**
     * @return void
     */
    public function echoDebugMemoryUsage(): void
    {
        $memoryUsage = memory_get_usage(true);
        $this->echoDebug(sprintf("Memory usage: %s Gb", $memoryUsage / 1024 / 1024 / 1024), [
            'B' => $memoryUsage,
            'Kb' => $memoryUsage / 1024,
            'Mb' => $memoryUsage / 1024 / 1024
        ]);
    }

}
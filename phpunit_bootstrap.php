<?php

if (!defined('YUSAM_HUB_IS_DEBUGGING')) {
    define('YUSAM_HUB_IS_DEBUGGING', true);
}

if (!defined('YUSAM_HUB_DEBUG_LOG_DIR')) {
    define('YUSAM_HUB_DEBUG_LOG_DIR', realpath(__DIR__ . DIRECTORY_SEPARATOR . "logs"));
}

require_once(__DIR__ . "/vendor/autoload.php");


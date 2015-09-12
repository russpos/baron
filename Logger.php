<?php

class Logger {

    public static function dump($data) {
        self::log(Colors::getInstance()->blue("~~~~ DUMP ~~~~"),"\n".print_r($data, true), 4);
    }

    public static function info($msg, $ns="") {
        self::log(Colors::getInstance()->cyan('[INFO ]'), $msg, $ns, 4);
    }

    public static function error($msg, $ns="") {
        self::log(Colors::getInstance()->red('[ERROR]'), $msg, $ns, null);
    }

    public static function warn($msg, $ns="") {
        self::log(Colors::getInstance()->yellow('[WARN ]'), $msg, $ns, null);
    }

    public static function debug($msg, $ns="") {
        self::log(Colors::getInstance()->green('[DEBUG]'), $msg, $ns, null);
    }

    private static function log($level_text, $message, $namespace, $level=null) {
        $uuid = Request::getRequestID();
        if (!empty($namespace)) {
            $namespace = "[$namespace]";
        }
        error_log("{$level_text} [{$uuid}] {$message} {$namespace}", $level);
    }

}

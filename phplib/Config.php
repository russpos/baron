<?php

class Config {

    protected static $data;

    protected static function getData() {
        global $CONFIG;
        if (!isset(self::$data)) {
            self::$data = $CONFIG;
        }
        return self::$data;
    }

    public static function get($key) {
        return self::getData()[$key];
    }
}

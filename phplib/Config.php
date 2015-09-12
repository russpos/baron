<?php

class Config {

    protected static $data;

    protected static function getData() {
        if (!isset(self::$data)) {
            require CONFIG_PATH;
            self::$data = $config;
        }
        return self::$data;
    }

    public static function get($key) {
        return self::getData()[$key];
    }
}

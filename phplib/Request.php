<?php

class Request {
    
    private $requestData;

    private static $uuid = false;

    public static function getRequestID() {
        if (!self::$uuid) {
            self::$uuid = uniqid();
        }
        return self::$uuid;
    }

    public function __construct(Array $params, Array $requestData) {
        $this->params = $params;
        $this->requestData = $requestData;
    }

    public function getParams() {
        return $this->params;
    }

    public function getParam($key, $default=null) {
        return static::getOrDefault($this->params, $key, $default);
    }

    public function get($param, $default=null) {
        return static::getOrDefault($this->requestData["get"], $param, $default);
    }

    public function getServer($param, $default=null) {
        return static::getOrDefault($this->requestData["server"], $param, $default);
    }

    public function getGetParams() {
        return $this->requestData["get"];
    }

    public function getPostParams() {
        return $this->requestData["post"];
    }

    public function getPostBodyString() {
        return file_get_contents('php://input');
    }

    public function getPostBodyData() {
        return json_decode($this->getPostBodyString(), true);
    }

    protected static function getOrDefault($arr, $key, $default) {
        if (array_key_exists($key, $arr)) {
            return $arr[$key];
        }
        return $default;
    }
}

<?php

class Response {

    const STATUS_OK = 200;

    protected $status = self::STATUS_OK;

    protected $headers = [];

    protected $body = '';

    public function __construct($body="", $status=self::STATUS_OK) {
        $this->body = $body;
        $this->status = $status;
    }

    public function getHeaders() {
        return array_merge($this->headers, $this->getDefaultHeaders());
    }

    public function getBody() {
        return $this->body;
    }

    public function setStatus($code) {
        $this->status = $code;
    }

    public function getDefaultHeaders() {
        return [];
    }

    public function getStatus() {
        return $this->status;
    }

}

<?php

class ApiClient_Response {

    private $status;
    private $body;
    private $headers;

    public function __construct($status, $body, $headers) {
        $this->status  = $status;
        $this->body    = $body;
        $this->headers = $headers;
    }

    public function getStatus() {
        return $this->status;
    }

    public function getBody() {
        return $this->body;
    }

    public function getHeaders() {
        return $this->headers;
    }

}

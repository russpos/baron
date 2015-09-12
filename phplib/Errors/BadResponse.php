<?php

class Errors_BadResponse extends Exception {

    public function __construct($response, Array $headers, $url) {
        $this->response = $response;
        $this->headers  = $headers;
        $this->url      = $url;
    }

    public function getStatusCode() {
        return $this->headers['code'];
    }

    public function getUrl() {
        return $this->url;
    }

    public function getResponse() {
        return $this->response;
    }

}

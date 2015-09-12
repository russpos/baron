<?php

class Response_JSON extends Response {

    protected $data;

    public function __construct($data=[], $status=self::STATUS_OK) {
        $this->data = $data;
        $this->status = $status;
    }

    public function getBody() {
        return json_encode($this->data);
    }

    public function getDefaultHeaders() {
        return ["Content-type: application/json"];
    }

}

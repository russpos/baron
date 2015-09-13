<?php

class ApiClient_Github extends ApiClient {


    protected static function getDefaultReturnType() {
        return self::RETURN_JSON;
    }

    protected static function getHost() {
        return "api.github.com";
    }

    protected static function getApiPrefix() {
        return "";
    }

    public function getAccessData() {
        return [];
    }

}

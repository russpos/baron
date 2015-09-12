<?php

class Redirect extends Exception {

    private $url;

    public function __construct($url) {
        $this->url = EnsureType::string($url);
    }



    public function getUrl() {
        return $this->url;
    }


}

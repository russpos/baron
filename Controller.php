<?php

abstract class Controller {

    public static $params = [];

    protected abstract function handle(Request $request);

    protected $client = false;

    public static function run(Array $params=[]) {
        $controller = new static();
        $controller->go($params, [
            "server"  => $_SERVER,
            "get"     => $_GET,
            "post"    => $_POST,
            "request" => $_REQUEST,
        ]);
    }

    protected function preHandle() {

    }

    protected function postHandle() {

    }

    protected function log_info($msg) {
        Logger::info($msg, get_class($this));
    }

    protected function log_error($msg) {
        Logger::error($msg, get_class($this));
    }

    protected function log_debug($msg) {
        Logger::debug($msg, get_class($this));
    }

    protected function log_warn($msg) {
        Logger::warn($msg, get_class($this));
    }

    public function go(Array $params, Array $requestData) {

        $request = new Request($params, $requestData);

        try {
            session_start();

            $this->postHandle();
            $response = $this->handle($request);

            if (!($response instanceof Response)) {
                Logger::error("WARNING: Non-response returned from controller!, got: ".get_class($response));
            }
            $this->postHandle();
        } catch (Redirect $e) {
            header("Location: ".$e->getUrl());
            return;

        } catch (Errors_BadResponse $e) {
            header("X-Origin-Url: {$e->getUrl()}");
            $response =  new Response_JSON($e->getResponse(), $e->getStatusCode);
        }

        // Serve the page
        http_response_code($response->getStatus());
        foreach ($response->getHeaders() as $header) {
            header($header);
        }
        echo $response->getBody();
    }
}

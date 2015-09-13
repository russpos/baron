<?php

abstract class ApiClient {

    const HTTP_GET   = "GET";
    const HTTP_PATCH = "PATCH";
    const HTTP_POST  = "POST";
    const HTTP_PUT   = "PUT";

    const RETURN_JSON = "JSON";
    const RETURN_URL  = "URL";

    private static $http_methods = [
        self::HTTP_GET,
        self::HTTP_PATCH,
        self::HTTP_POST,
        self::HTTP_PUT,
    ];

    abstract static protected function getHost();
    abstract static protected function getApiPrefix();
    abstract static protected function getDefaultReturnType();

    abstract protected function getAccessData();

    protected static function generateUrl($path, $qs=[]) {
        $url = "https://" . static::getHost() . $path;
        if (!empty($qs)) {
            $url .= '?' . http_build_query($qs);
        }
        return $url;
    }

    protected static function generateApiUrl($path, $qs=[]) {
        return static::generateUrl(static::getApiPrefix() . $path, $qs);
    }

    public function __call($method, $args) {
        $method = strtoupper($method);
        if (in_array($method, self::$http_methods)) {
            return $this->__handleRequestFunction($verb, $args);
        }
        $class_name = get_class($this);
        $msg = "$class_name has no method '$method'";
        throw new BadMethodCallException($msg);
    }

    private function __handleRequestFunction($verb, $args) {
        $is_raw = false;
        list($url_path, $data) = $args;
        $verb = strtoupper($verb);
        if ($verb == static::HTTP_GET) {
            $url_data = $data;
            $body_data = [];
        } else {
            $url_data = [];
            $body_data = $data;
        }

        $url_data = array_merge($url_data, $this->getAccessData());
        $url = ($is_raw) ?
            static::generateUrl($url_path, $url_data) :
            static::generateApiUrl($url_path, $url_data)
        ;

        return $this->makeRequest(
            $verb,
            $url,
            $body_data,
            static::getDefaultReturnType()
        );
    }

    protected function makeRequest($http_verb, $url, $data=[], $returnType=self::RETURN_JSON) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 1);

        curl_setopt($ch, CURLOPT_USERAGENT, 'Baron ApiClient 0.0.1');

        Logger::debug("Attempting to $http_verb $url");

        if ($http_verb == static::HTTP_PATCH) {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
        } else if ($http_verb == static::HTTP_PUT) {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        }

        if ($http_verb == static::HTTP_POST || $http_verb == static::HTTP_PATCH || $http_verb == static::HTTP_PUT) {
            $data_string = (is_string($data)) ? $data : json_encode($data);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json-patch+json',
                'Content-Length: ' . strlen($data_string),
            ]);
            Logger::info("POST data: $data_string");
        }

        $response     = curl_exec($ch);
        $header_size  = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $headers      = substr($response, 0, $header_size);
        $body         = substr($response, $header_size);
        $header_array = static::parseHeaders($headers);
        Logger::info("Request status: {$header_array['Status']} ({$header_array['code']})");

        if ($returnType === static::RETURN_URL) {
            parse_str($body, $response_data);
        } else if ($returnType === static::RETURN_JSON) {
            $response_data = json_decode($body, true);
            if ($response_data == false) {
                Logger::warn("Error parsing response: $body");
            }
        } else {
            throw new InvalidArgumentException("$returnType is not a known ApiClient return type");
        }

        // Throw an exception on a bad request
        if ($header_array['code'] >= 400) {
            throw new Errors_BadResponse($response_data, $header_array, $url);
        }

        return $response_data;
    }

    private static function parseHeaders($headers) {
        $headers = trim($headers);
        $header_array = [];

        $headers = explode("\n", $headers);
        foreach ($headers as $header) {
            list($name, $value) = explode(':', $header, 2);
            $header_array[trim($name)] = trim($value);
        }
        preg_match('~^\d+~', $header_array['Status'], $m);
        $header_array['code'] = (int) $m[0];
        return $header_array;
    }

}

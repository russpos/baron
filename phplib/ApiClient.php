<?php

abstract class ApiClient {

    const HTTP_GET   = "GET";
    const HTTP_PATCH = "PATCH";
    const HTTP_POST  = "POST";
    const HTTP_PUT   = "PUT";

    const RETURN_JSON = "JSON";
    const RETURN_URL  = "URL";

    abstract static protected function getHost();
    abstract static protected function getApiPrefix();
    abstract static protected function getDefaultReturnType();

    abstract protected function getAccessData();

    protected static function generateUrl($path, $qs=[]) {
        $url = "https://" . self::getHost() . $path;
        if (!empty($qs)) {
            $url .= '?' . http_build_query($qs);
        }
        return $url;
    }

    protected static function generateApiUrl($path, $qs=[]) {
        return self::generateUrl(self::getApiPrefix() . $path, $qs);
    }

    // TODO -- see if we can replace post, patch, get, put, and getraw with this
    // Totally untested method
    private function __handleRequestFunction($verb, $is_raw, $url_path, $data) {
        $verb = strtoupper($verb);
        if ($verb == self::HTTP_GET) {
            $url_data = $data;
            $body_data = [];
        } else {
            $url_data = [];
            $body_data = $data;
        }

        $url_data = array_merge($url_data, $this->getAccessData());
        $url = ($is_raw) ?
            self::generateUrl($url_path, $url_data) :
            self::generateApiUrl($url_path, $url_data)
        ;

        return $this->makeRequest(
            $verb,
            $url,
            $body_data,
            self::getDefaultReturnType()
        );
    }

    public function get($url, $data=[]) {
        $data = array_merge($data, $this->getAccessData());
        return $this->makeRequest(
            self::HTTP_GET,
            self::generateApiUrl($url, $data),
            [],
            self::getDefaultReturnType()
        );
    }

    public function getRaw($url, $data=[]) {
        $data = array_merge($data, $this->getAccessData());
        return $this->makeRequest(
            self::HTTP_GET,
            self::generateUrl($url, $data),
            [],
            self::getDefaultReturnType()
        );
    }

    public function post($url, $data=[]) {
        $access = $this->getAccessData();
        return $this->makeRequest(
            self::HTTP_POST,
            self::generateApiUrl($url, $access),
            $data,
            self::getDefaultReturnType()
        );
    }

    public function put($url, $data=[]) {
        $access = $this->getAccessData();
        return $this->makeRequest(
            self::HTTP_PUT,
            self::generateApiUrl($url, $access),
            $data,
            self::getDefaultReturnType()
        );
    }

    public function patch($url, $data=[]) {
        $access = $this->getAccessData();
        return $this->makeRequest(
            self::HTTP_PATCH,
            self::generateApiUrl($url, $access),
            $data,
            self::getDefaultReturnType()
        );
    }

    protected function makeRequest($http_verb, $url, $data=[], $returnType=self::RETURN_JSON) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 1);

        if ($http_verb == self::HTTP_PATCH) {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
        } else if ($http_verb == self::HTTP_PUT) {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        }

        if ($http_verb == self::HTTP_POST || $http_verb == self::HTTP_PATCH || $http_verb == self::HTTP_PUT) {
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
        $header_array = self::parseHeaders($headers);
        Logger::info("Request status: {$header_array['Status']} ({$header_array['code']})");

        if ($returnType === self::RETURN_URL) {
            parse_str($body, $response_data);
        } else if ($returnType === self::RETURN_JSON) {
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


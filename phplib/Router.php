<?php

class Router {

    public static function route(array $routes) {
        $route_path = $_SERVER['PHP_SELF'];

        foreach ($routes as $route_string => $controller) {
            list($method, $route) = explode(' ', $route_string, 2);

            if (
                // First verify method
                ($method === '*' || $method === $_SERVER['REQUEST_METHOD']) &&

                // Then regex on the route itself
                (preg_match("~^$route$~", $route_path, $matches))
            ) {
                array_shift($matches);
                $param_keys = $controller::$params;
                $controller::run(array_combine($param_keys, $matches));
                return true;
            }
        }
        return false;
    }
}

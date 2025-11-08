<?php

namespace barephrame\src\lib\components;

class Route {
    static protected array $routes = [];
    static protected string $not_found_route = '*.*';

    static public function Get(string $route, string $class, string $fn) :void {
       if(!isset(self::$routes['GET'])) self::$routes['GET'] = [];
       if($route == self::$not_found_route) {
        self::$routes['GET_NOT_FOUND'] = ['CLASS' => $class, 'FN' => $fn];
        return;
       }
       self::$routes['GET'][] = ['ROUTE' => $route, 'CLASS' => $class, 'FN' => $fn, 'PARAMS' => []];
    }

    static public function Post(string $route, string $class, string $fn) :void {
        if(!isset(self::$routes['GET'])) self::$routes['POST'] = [];
        if($route == self::$not_found_route) {
            self::$routes['POST_NOT_FOUND'] = ['CLASS' => $class, 'FN' => $fn];
            return;
           }
        self::$routes['POST'][] = ['ROUTE' => $route, 'CLASS' => $class, 'FN' => $fn, 'PARAMS' => []];
    }

    static public function _GetRoutes(string $method) :array|bool {
        $method = strtoupper($method);
        $result = false;
        if(isset(self::$routes[$method])) {
            $result = [self::$routes[$method]];
        }
        if($result && isset(self::$routes["{$method}_NOT_FOUND"])) {
            $result[] = self::$routes["{$method}_NOT_FOUND"];
        }
        return $result;
    }
}
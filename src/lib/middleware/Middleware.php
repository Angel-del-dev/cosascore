<?php

namespace cosascore\src\lib\middleware;
use cosascore\src\lib\auth\SimpleAuth;

class Middleware {
    static protected $middleware = [];
    static public function on(string $key, callable $function) {
        if(!isset(self::$middleware[$key])) {
            print_r("Middleware '{$key}' is not defined");
            exit;
        }

        //$className = ''..'';
        $middleware = new self::$middleware[$key]['className']();
        $function_name = self::$middleware[$key]['function'];
        if($middleware::$function_name()) $function();
    }

    static public function define(string $key, $className, string $function):void {
        self::$middleware[$key] = [
            'className' => $className,
            'function' => $function
        ];
    }

    static public function getAll() { return self::$middleware; }

    static public function define_essentials() {
        self::define('Simple-Auth', SimpleAuth::class, 'isAuthMiddleware');
    }
}
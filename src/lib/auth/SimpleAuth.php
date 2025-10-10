<?php

namespace cosascore\src\lib\auth;

class SimpleAuth {
    static public function isAuthMiddleware():bool {
        $_SERVER['AuthParams'] = self::user();
        return self::isAuth();
    }
    static public function isAuth():bool {
        return isset($_SESSION['user']);
    }

    static public function login(array $data) {
        $_SESSION['user'] = $data;
    }

    static public function user():array|bool { return $_SESSION['user'] ?? false; }
    static public function destroy():void { session_unset(); }
}
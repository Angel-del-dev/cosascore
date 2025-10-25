<?php

namespace cosascore\src\lib\components;

use stdClass;

class Utils {
    static function URL(string $url):array {
        $p_url = explode('/', $url);
        $f_url = [];
        foreach($p_url as $k) {
            $k = trim($k);
            if($k == '') continue;
            $f_url[] = $k;
        }

        return $f_url;
    }
    static function INI(string $path):array|bool {
        return parse_ini_file($path, true);
    }
}

class Request {
    public stdClass $server;
    public function __construct() {
        $this->server = (object) $_SERVER;
        $this->server->POST = isset($_POST['req']) ? json_decode(base64_decode($_POST['req'])) : new stdClass();
        $this->server->GET = $_GET;
    }
    /**
     * Debug function must not be used for 
     * production purposes
     */
    public function Debug():void {
        print_r('<pre>');
        print_r($this->server);
        print_r('</pre>');
    }

    public function RequestMethod():string {
        return $this->server->REQUEST_METHOD;
    }

    public function Get(string $parameter):stdClass|string|bool {
        return isset($this->server->$parameter) ? $this->server->$parameter : false;
    }

    public function GetAll():stdClass {        
        return $this->server;
    }
}

class Response {
    static public function Format():stdClass {
        $result = new stdClass();

        $result->ok = true;
        $result->message = '';

        return $result;
    }
}
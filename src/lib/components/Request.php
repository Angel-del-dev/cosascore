<?php

namespace barephrame\src\lib\components;

use stdClass;

class Request {
    public stdClass $server;
    public function __construct() {
        $this->server = (object) $_SERVER;
        $this->server->POST = isset($_POST['req']) ? json_decode(base64_decode($_POST['req'])) : json_decode(file_get_contents("php://input"));
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

    public function Get(string $parameter):stdClass|array|string|bool {
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
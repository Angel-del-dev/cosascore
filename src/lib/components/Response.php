<?php
namespace barephrame\src\lib\components;

use stdClass;

class Response {
    static public function Format():stdClass {
        $result = new stdClass();

        $result->ok = true;
        $result->message = '';

        return $result;
    }
}
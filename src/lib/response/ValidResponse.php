<?php

namespace barephrame\src\lib\response;
use stdClass;

class ValidResponse {
    static public function GetResponseLinker():array {
        return [
            'text/json' => 'json_encode',
            'text/xml' => '',
            'text/html' => ''
        ];
    }

    static public function GetValidResponseType(string $type):stdClass {
        $linker = self::GetResponseLinker();
        if($type == '' || !isset($linker[$type])) {
            print_r("Response type '{$type}' is not valid");
            exit;
        }

        $typeResult = new stdClass();
        $typeResult->type = $type;
        $typeResult->encode = $linker[$type];

        return $typeResult;
    }
}
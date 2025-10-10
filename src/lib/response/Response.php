<?php

namespace lib\Response;

use src\lib\response\ResponseType;
use src\lib\response\ValidResponse;

class Response {
    static public function Handle($response, string $type = 'text/json'):ResponseType {
        return new ResponseType($response, ValidResponse::GetValidResponseType($type));                
    }
}
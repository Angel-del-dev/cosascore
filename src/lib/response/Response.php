<?php

namespace barephrame\src\lib\response;

use barephrame\src\lib\response\ResponseType;
use barephrame\src\lib\response\ValidResponse;

class Response {
    static public function Handle($response, string $type = 'text/json'):ResponseType {
        return new ResponseType($response, ValidResponse::GetValidResponseType($type));                
    }
}
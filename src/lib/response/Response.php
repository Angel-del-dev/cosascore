<?php

namespace cosascore\src\lib\response;

use cosascore\src\lib\response\ResponseType;
use cosascore\src\lib\response\ValidResponse;

class Response {
    static public function Handle($response, string $type = 'text/json'):ResponseType {
        return new ResponseType($response, ValidResponse::GetValidResponseType($type));                
    }
}
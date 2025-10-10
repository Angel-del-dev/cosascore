<?php

namespace src\lib\Response;
use stdClass;

class ResponseType {
    protected $response;
    protected string $type;
    protected string $encode;
    public function __construct($response, stdClass $type) {
        $this->response = $response;
        $this->type = $type->type;
        $this->encode = $type->encode;
    }

    public function getResponse() {
        header("Content-type: {$this->type}");
        $response = $this->response;
        switch($this->encode) {
            case 'json_encode':
                $response = json_encode($response);
            break;
            default:
                // Pass
            break;
        }
        return $response;
    }
}
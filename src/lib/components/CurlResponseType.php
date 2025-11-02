<?php

namespace cosascore\src\lib\components;

class CurlResponseType {
    private array $response;
    public function __construct(string $response = '', int $header_size = 0) {
        $this->response = [];
        if(strlen(trim($response)) > 0 && $header_size > 0) $this->parse($response, $header_size);
    }

    private function parse(string $response, int $header_size):void {
        $headers = substr($response, 0, $header_size);
        $headers_array = explode("\n", $headers);

        $this->parse_status(array_shift($headers_array));

        foreach($headers_array as $header) {
            $line = trim($header);
            if($line === '') continue;

            list($key, $value) = explode(':', $line, 2);
            $this->response[$key] = $value;
        }
        $this->response['Data'] = trim(substr($response, $header_size));
    }

    private function parse_status(string $status) {
        list($protocol, $statuscode, $statusmessage) = explode(' ', $status);
        $this->response['Status'] = [
            'Protocol' => $protocol,
            'Code' => intval($statuscode),
            'Message' => $statusmessage
        ];
    }

    public function Get(string $key):string|array|bool {
        if(!isset($this->response[$key])) return false;
        return $this->response[$key];
    }

    public function GetAll():array {
        return $this->response;
    }

    public function SetStatusManually(int $statuscode, string $statusmessage):void {
        $this->response['Status'] = [
            'Code' => $statuscode,
            'Message' => $statusmessage
        ];
    }
}
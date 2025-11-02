<?php

namespace cosascore\src\lib\abstractions;

use cosascore\src\lib\components\CurlResponseType;
use cosascore\src\lib\enums\CurlMethods;

class Curl {
    private string $url;
    private CurlMethods $method;
    private array $params;
    private array $headers;
    public function __construct(string $url) {
        $this->url = $url;
        $this->method = CurlMethods::POST;
        $this->params = [];
        $this->headers = [];
    }

    public function Method(CurlMethods $method):void {
        $this->method = $method;
    }

    public function AddHeader(...$headers):void {
        foreach($headers as $header) {
            $this->headers[] = $header;
        }
    }

    public function AddParams(array $params):void {
        $this->params = $params;
    }

    public function Execute():CurlResponseType {
        $post_params = null;
        if(in_array($this->method->value, [CurlMethods::GET->value, CurlMethods::POST->value])) {
            $this->url .= http_build_query($this->params);
        } else {
            $post_params = json_encode($this->params);
        }
        $ch = curl_init($this->url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $this->method->value);
        curl_setopt($ch, CURLOPT_HEADER, true);
        if(count($this->headers) > 0) curl_setopt($ch, CURLOPT_HTTPHEADER, $this->headers);
        if(!is_null($post_params)) curl_setopt($ch, CURLOPT_POSTFIELDS, $post_params);
        
        $response = curl_exec($ch);
        $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);

        if(curl_errno($ch)) {
            $response = new CurlResponseType();
            $response->SetStatusManually(500, curl_error($ch));
            return $response;
        }

        curl_close($ch);
        
        $response = new CurlResponseType($response, $headerSize);

        return $response;
    }
}
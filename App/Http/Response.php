<?php

namespace App\Http;

class Response
{
    private string $contentType;
    private array $headers = [];
    private int $code = 200;
    private string | array $responseContent;
    public function __construct(
        int $code,
        string | array $responseContent,
        string $contentType = "text/html"
    )
    {
        $this->code = $code;
        $this->responseContent = $responseContent;
        $this->setContentType($contentType);
    }

    public function setContentType(string $contentType)
    {
        $this->contentType = $contentType;
        $this->addHeader('Content-Type', $contentType);
    }
    public function addHeader(string $key, string $value)
    {
        $this->headers[$key] = $value;
    }
    public function sendResponse()
    {
        $this->sendHeaders();
        switch($this->contentType) {
            case 'text/html':
                echo $this->responseContent;
                exit;
        }
    }
    private function sendHeaders()
    {
        http_response_code($this->code);
        foreach($this->headers as $key => $value) {
            header($key . ': ' . $value);
        }
    }  
}
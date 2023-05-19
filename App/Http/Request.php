<?php

namespace App\Http;

class Request
{
    public readonly string $httpMethod;
    public readonly Router $router;
    private string $uri;
    public readonly array $queryParams;
    public readonly array $postVars;
    public readonly array $headers;

    public function __construct($router)
    {
        $this->router = $router;
        $this->queryParams = $_GET ?? [];
        $this->postVars = $_POST ?? [];
        $this->headers = getallheaders();
        $this->httpMethod = $_SERVER['REQUEST_METHOD'] ?? "";
        $this->setUri();
    }

    private function setUri()
    {
        $this->uri = $_SERVER['REQUEST_URI'] ?? "";
        $xUri = explode("?", $this->uri);
        $this->uri = $xUri[0];
    }

    public function getRouter() : Router
    {
        return $this->router;
    }

    public function getUri(): string
    {
        return $this->uri;
    }
}
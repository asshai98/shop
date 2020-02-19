<?php

namespace App\Core;

final class Route
{
    private $requestMethod = 'ANY';
    private $controller;
    private $method; //metod kontrolera
    private $pattern;

    private function __construct(string $requestMethod, string $controller, string $method, string $pattern)
    {
        $this->requestMethod = $requestMethod;
        $this->controller = $controller;
        $this->method = $method;
        $this->pattern = $pattern;
    }

    public static function get(string $pattern, string $controller, string $method):Route
    {
        return new Route('GET', $controller, $method, $pattern);
    }

    public static function post(string $pattern, string $controller, string $method):Route
    {
        return new Route('POST', $controller, $method, $pattern);
    }

    public static function any(string $pattern, string $controller, string $method):Route
    {
        return new Route('GET|POST', $controller, $method,$pattern);
    }

    public function matches(string $method, string $url): bool
    {
        if (!preg_match('/^' . $this->requestMethod . '$/', $method)) {
            return false;
        }

        return boolval(preg_match($this->pattern, $url));
    }

    public function getControllerName():string{
        return $this->controller;
    }

    public function getMethodName():string{
        return $this->method;
    }

    public function &extractArguments(string $url):array{
        $matches=[];
        $arguments = [];
        preg_match_all($this->pattern, $url, $matches);

        unset($matches[0]);
        foreach ($matches as $match) {
            $arguments[] = $match[0];
        }

        return $arguments;
    }
}

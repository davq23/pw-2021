<?php 
namespace App;

interface Router
{
    /**
     * @param string $method
     * @param string $pattern
     * @param $controllerClass
     * @param string $controllerMethod
     * @return mixed
     */
    public function registerRoute(
        string $method, 
        string $pattern, 
        $controllerClass, 
        $controllerMethod = 'index'
    );

    /**
     * @return array|null
     */
    public function run(): ?array;
}
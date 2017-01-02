<?php

namespace Core;

class NewFrontController
{
    /**
     *
     * @var type 
     */
    protected $routes;

    /**
     *
     * @var type
     */
    protected $controller;

    /**
     *
     * @var type
     */
    protected $action;

    /**
     *
     * @var type 
     */
    protected $params = array();

    public function __construct()
    {
        $this->setRouteList();
        $this->dispatch();
    }

    public function dispatch()
    {
        $uri    = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
        $path   = preg_replace('[^a-zA-Z0-9]', '', $uri);
        $method = $_SERVER['REQUEST_METHOD'];

        $segments = explode("/", $path, 3);

        $mark = null;

        if (!empty($this->routes)) {
            if (array_key_exists(0, $segments) && empty($segments[0])) {
                echo 'this is the root!';
                $mark = '/';
            } elseif (in_array(($segments[0]), array_keys($this->routes))) {
                echo 'match single word route: '.$segments[0];
                $mark = $segments[0];
            } elseif (array_key_exists(1, $segments) && in_array(($segments[0].'/'.$segments[1]),
                    array_keys($this->routes))) {
                echo 'match '.$segments[0].'/'.$segments[1];
                $mark = $segments[0].'/'.$segments[1];
            } else {
                throw new \Exception("Not Found Exception", 404);
            }

            $this->setController($mark);
            $this->setAction($mark);
            $this->setParams($mark);
        } else {
            throw new \Exception("There's no routes defined...", 500);
        }
    }

    public function setRouteList()
    {
        /*
         * @TODO the base path, public path, app path need declare somewhere
         */
        $routes       = require_once $_SERVER['DOCUMENT_ROOT'].'../../app/Http/routes.php';
        $this->routes = $routes;
        return $this;
    }

    public function setHttpMethod($httpMethod)
    {

    }

    public function setController($key)
    {
        $controller = $this->routes[$key]['processor'];

        if (!class_exists($controller)) {
            throw new \Exception(
            "The action controller '$controller' has not been defined."
            );
        }

        return $this->controller = $controller;
    }

    public function setAction($key)
    {
        $action = 'index';
        
        if(array_key_exists('action', $this->routes[$key]) || !empty($this->routes[$key]['action'])){
            $action = $this->routes[$key]['action'];
        }

        $reflector = new \ReflectionClass($this->controller);

        if (!$reflector->hasMethod($action)) {
            throw new \Exception(
                "The controller method '$action' has been not defined."
            );
        }
        
        return $this->action = $action;
    }

    public function setParams($key)
    {
        $params = null;
        
        if(array_key_exists('params', $this->routes[$key]) && !empty($this->routes[$key]['params'])){
            $params = $this->routes[$key]['params'];
        }

        return $this->params = $params;
    }

    public function fire()
    {
        return [
            'controller' => $this->controller,
            'action' => $this->action,
            'params' => $this->params
        ];
    }
}

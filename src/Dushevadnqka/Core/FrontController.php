<?php

namespace Core;

/**
 * Description of FrontController
 *
 * @author dushevadnqka
 */
final class FrontController
{
    private static $instance = null;
    private $basePath;
    private $controller;
    private $action = 'index';
    private $params = [];

    private function __construct()
    {
        $config           = Config::getInstance();
        $this->basePath   = $config->routes['application_domain'];
        $this->controller = $config->routes['base_controller'];

        $this->dispatch();
    }

    public function dispatch()
    {
        $path = trim(parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH), "/");
        $path = preg_replace('[^a-zA-Z0-9]', "", $path);

        if (strpos($path, $this->basePath) === 0) {
            $path = substr($path, strlen($this->basePath));
        }

        $segments = explode("/", $path, 3);

        if (array_key_exists(0, $segments) && !empty($segments[0])) {
            /*
             * controller
             */
            $this->setController($segments[0]);
        }

        if (array_key_exists(1, $segments)) {
            /*
             * action
             * @TODO: if is in camil case ->transform it to dashed
             */
            $this->setAction($segments[1]);
        }

        if (array_key_exists(2, $segments)) {
            /*
             * params
             */
            $this->setParams(explode("/", $segments[2]));
        }
    }

    /**
     *
     * @param type $controller
     * @return string
     * @throws \Exception
     */
    public function setController($controller)
    {
        $controller = ucfirst(strtolower($controller))."Controller";

        $controller = 'App\\Http\\Controllers\\'.$controller;

        if (!class_exists($controller)) {
            throw new \Exception(
            "The action controller '$controller' has not been defined.");
        }

        return $this->controller = $controller;
    }

    public function setAction($action)
    {
        $reflector = new \ReflectionClass($this->controller);

        if (!$reflector->hasMethod($action)) {
            throw new InvalidArgumentException(
            "The controller action '$action' has been not defined.");
        }
        $this->action = $action;
    }

    public function setParams(array $params)
    {
        $this->params = $params;
    }

    public function fire()
    {
        call_user_func_array([new $this->controller, $this->action],
            $this->params);
    }

    /**
     *
     * @return Core\FrontController
     */
    public static function getInstance()
    {
        if (self::$instance == NULL) {
            self::$instance = new FrontController();
        }
        return self::$instance;
    }
}

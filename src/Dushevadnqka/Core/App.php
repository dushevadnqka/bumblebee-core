<?php

namespace Core;

/**
 * Description of App
 *
 * @author dushevadnqka
 */
class App
{
    private static $instance = null;
    private $config          = null;
    private $dbConnections   = [];

    private function __construct()
    {
        $this->config = Config::getInstance();
        if ($this->config->getConfigFolder() == null) {
            $this->setConfigFolder(getcwd().'/../config');
        }
    }

    public function setConfigFolder($path)
    {
        $this->config->setConfigFolder($path);
    }

    public function run()
    {
        $controller = FrontController::getInstance();
        $controller->fire();
    }

   /**
    *
    * @return Core/App
    */
    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new App();
        }
        return self::$instance;
    }
}

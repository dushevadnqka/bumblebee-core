<?php

namespace Core;

/**
 * Description of App
 *
 * @author dushevadnqka
 */
class App
{
    private $config          = null;
    private $dbConnections   = [];

    public function __construct()
    {
        $this->config = new Config();
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
        $controller = new FrontController($this->config);
        $controller->fire();
    }
}

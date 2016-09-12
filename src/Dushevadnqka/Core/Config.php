<?php

namespace Core;

/**
 * Description of Config
 *
 * @author dushevadnqka
 */
class Config
{
    private static $instance = null;
    private $configArray     = [];
    private $configFolder    = null;

    private function __construct()
    {

    }

    public function getConfigFolder()
    {
        return $this->configFolder;
    }

    public function setConfigFolder($configFolderParam)
    {
        if (!$configFolderParam) {
            throw new \Exception("Empty config folder path");
        }
        $configFolder = realpath($configFolderParam);
        if ($configFolder != FALSE && is_dir($configFolder) && is_readable($configFolder)) {
            $this->configArray  = [];
            $this->configFolder = $configFolder.DIRECTORY_SEPARATOR;
        } else {
            throw new \Exception("Config directory read error: ".$configFolderParam);
        }
    }

    public function includeConfigFile($path)
    {
        if (!$path) {
            throw new \Exception("Error include CNF");
        }
        $file = realpath($path);
        if ($file != FALSE && is_file($file) && is_readable($file)) {
            $_basename                     = explode('.php', basename($file))[0];
            $this->configArray[$_basename] = include $file;
        } else {
            //@TODO
            throw new \Exception("Config file read error: ".$path);
        }
    }

    public function __get($name)
    {
        if (!isset($this->configArray[$name])) {
            $this->includeConfigFile($this->configFolder.$name.'.php');
        }
        if (array_key_exists($name, $this->configArray)) {
            return $this->configArray[$name];
        }
        return null;
    }

    /**
     *
     * @return Core\Config
     */
    public static function getInstance()
    {
        if (self::$instance == NULL) {
            self::$instance = new Config();
        }
        return self::$instance;
    }
}

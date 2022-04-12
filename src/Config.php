<?php
namespace Agregalel\Lturtle\Console;

class Config {
    private $config_path = '';
    public function __construct() {
        $this->config_path = 'config.json';
    }

    public function get($key) {
        $config = json_decode(file_get_contents($this->config_path), true);
        return $config[$key];
    }
    public function getAll() {
        $config = json_decode(file_get_contents($this->config_path), true);
        return $config;
    }

    public function getPaths()
    {
        $config = json_decode(file_get_contents($this->config_path), true);
        return $config['pathSrc'];
    }

    public function getStyleType()
    {
        $config = json_decode(file_get_contents($this->config_path), true);
        return $config['styleType'];
    }
}
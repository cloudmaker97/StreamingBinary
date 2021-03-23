<?php
namespace Configuration;
class ConfigurationLoader {
    private $_Configuration;

    public function __construct()
    {
        $FileContent = file_get_contents(__DIR__."/Config.json");
        $this->_Configuration = json_decode($FileContent);
    }

    public function GetConfiguration() {
        return $this->_Configuration;
    }

    static function Create(): ConfigurationLoader {
        return new ConfigurationLoader();
    }
}
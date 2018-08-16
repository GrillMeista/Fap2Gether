<?php

class Autoloader
{
    public function __construct()
    {
        spl_autoload_register(array($this, 'load'));
    }

    public static function register()
    {
        new Autoloader();
    }

    public function load($class)
    {
        foreach (include('config/autoloader_config.php') as $key => $val)
        {
            if($key == $class){
                require_once($val);
            }
        }

    }

}
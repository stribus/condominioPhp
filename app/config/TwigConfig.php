<?php

namespace app\config;

class TwigConfig implements Configuration
{   
    protected Array $config;

    public function __construct()
    {
        $this->config =  [
            'path' => 'app/views',
            'cache' => 'app/temp/cache',
            'debug' => true,
            'auto_reload' => true,
            'strict_variables' => false,
            'autoescape' => false,
            'charset' => 'utf-8',            
        ];
    }

    public function getConfig()
    {
        return $this->config;
    }
}
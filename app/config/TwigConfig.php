<?php

namespace app\config;

class TwigConfig extends Configuration
{   
    
    public function getConfig()
    {
        return [
            'path' => 'app/views',
            'cache' => 'app/temp/cache',
            'debug' => true,
            'auto_reload' => true,
            'strict_variables' => false,
            'autoescape' => false,
            'charset' => 'utf-8',            
        ];
    }
}
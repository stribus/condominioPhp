<?php

namespace app\config\connection;

use app\config\Configuration;

class ConnectionConfig implements Configuration
{
    protected Array $dataBase;

    public function getConfig()
    {
        return $this->dataBase;
    }

}
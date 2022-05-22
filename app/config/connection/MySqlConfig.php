<?php

namespace app\config\connection;

class MySqlConfig extends ConnectionConfig
{
    public function __construct()
    {
        $this->dataBase = [
            'server' => 'localhost',
            'user' => 'root',
            'pwd' => '',
            'database' => 'condominio',
        ];
    }

    public function getServer()
    {
        return $this->dataBase['server'];
    }

    public function getUser()
    {
        return $this->dataBase['user'];
    }

    public function getPwd()
    {
        return $this->dataBase['pwd'];
    }

    public function getDatabase()
    {
        return $this->dataBase['database'];
    }
}

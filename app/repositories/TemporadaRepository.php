<?php

namespace app\repositories;

use app\config\connection\MySqlConfig;
use app\repositories\connections\MySqlConnection;
use app\repositories\traits\Create;
use app\repositories\traits\Read;
use app\repositories\traits\Update;
use app\repositories\traits\Delete;



class TemporadaRepository
{
    use Read,Create,Update,Delete;

    protected $connection;
    protected $tableName = 'temporadas';

    public function getTemporadaAtiva():\app\entities\TemporadaEntity
    {
        $this->connection = new MySqlConnection(new MySqlConfig());
        $this->connection->connect();
        $this->select()->where('ativo = 1');
        $r = $this->getFirst(\app\entities\TemporadaEntity::class);
        $this->connection->disconnect();
        return $r;
    }
}
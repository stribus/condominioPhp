<?php

namespace app\repositories\connections;
use app\config\connection\ConnectionConfig;

abstract class Connection
{
    protected $config;
    protected \PDO|null $conexao;
    protected \PDOStatement|false $statement;
    protected $result;
    protected $sql;
    protected $params;
    protected $error;    

    public function __construct(ConnectionConfig $config)
    {
        
       $this->config = $config;
       $this->conexao = null;
       $this->statement = false;
       $this->result = null;
       $this->sql = null;
       $this->params = null;
       $this->error = null;
    }
    

    abstract public function connect();

    public function getError()
    {
        return $this->error;
    }

    abstract public function query($sql, $params = null):static;

    abstract public function disconnect();

    public function __destruct()
    {
        if ($this->conexao != null) {
            $this->disconnect();
            $this->conexao  = null;
        }
    }

    public function getResult()
    {
        return $this->result;
    }

    public function getStatement():\PDOStatement|false
    {
        return $this->statement;
    }    
    
    public function getLastInsertId()
    {
        return $this->conexao->lastInsertId();
    }

    public function getRowCount()
    {
        return $this->statement->rowCount();
    }
    
    abstract public function getAll(string $class = null);
}

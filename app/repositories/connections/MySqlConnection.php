<?php

namespace app\repositories\connections;

use app\config\connection\MySqlConfig;
use app\exceptions\SQLException;

class MySqlConnection extends Connection
{
    public function connect()
    {
        $conf = (fn ($conf): MySqlConfig => $conf)($this->config);
        $this->conexao = new \PDO('mysql:host='.$conf->getServer().';dbname='.$conf->getDatabase(), $conf->getUser(), $conf->getPwd());
        $this->conexao->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }

    public function query($sql, $params = null): static
    {
        $this->sql = $sql;
        $this->params = $params;
        $this->statement = $this->conexao->prepare($this->sql);
        if(!$this->statement->execute($this->params)){
            $this->error = $this->statement->errorInfo();
            throw new SQLException(print_r(['Erro'=>$this->error,'SQL'=>$this->sql], true));
            $this->statement = false;
        }

        return $this;
    }

    public function disconnect()
    {
        $this->conexao = null;
    }
   

    public function getAll(string $class = null,$ctor_args=null): array
    {
        if ($this->statement === false) {
            return [];
        }

        if ($class === null) {            
            $this->result = $this->statement->fetchAll(\PDO::FETCH_ASSOC);
        }else {
            $this->result = $this->statement->fetchAll(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE , $class, $ctor_args);
        }
        $this->statement->closeCursor();
        $this->statement = false;
        return $this->result;
    }

  
}
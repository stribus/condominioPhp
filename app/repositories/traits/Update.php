<?php

namespace app\repositories\traits;

use app\exceptions\SQLException;

trait Update
{
    public function update($idvalue,$attributes){
        
        if(!isset($idvalue)){
            throw new \Exception("informe o ID");
        }

        $sql = "update ".$this->tableName." set " ;
        foreach($attributes as $field => $value){
            $sql.= $field."= ?, ";
        }
        $sql = rtrim($sql,', ');
        $sql .= " where ".$this->PK." = ? ";
        
        $attributes['primarykey'] = $idvalue;
        return $this->connection->query($sql, array_values($attributes))->getRowCount();        
    }
}
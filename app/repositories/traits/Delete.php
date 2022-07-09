<?php

namespace app\repositories\traits;

trait Delete
{
    public function delete($idvalue){

        
        if(!isset($idvalue)){
            throw new \Exception("informe o ID");
        }
        
        $sql = "delete from {$this->tableName} where {$this->PK} = ? ";       
        return $this->connection->query($sql,[$idvalue])->getRowCount();
    }
    
    
    public function deleteWhere($field,$value){
       
        if(!isset($idvalue)){
            throw new \Exception("informe o valor");
        }        
        $sql = "delete from {$this->tableName} where $field = ? ";       
        return $this->connection->query($sql,[$idvalue])->getRowCount();

    }
}
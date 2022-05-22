<?php

namespace app\repository\traits;

use app\exceptions\SQLException;

trait Create
{
    public function create($attributes) {

        $sql = "insert into " . $this->tableName . " (";
        $sql .= implode(',', array_keys($attributes)) . ') values(';
        $sql .= implode(',', array_fill_keys(array_keys($attributes), '?')) . ');';

        $id = $this->connection->query($sql, array_values($attributes))->getLastInsertId();       
        return $id;
    }
}
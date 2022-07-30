<?php

namespace app\repositories;

use app\config\connection\MySqlConfig;
use app\repositories\connections\MySqlConnection;
use app\repositories\traits\Create;
use app\repositories\traits\Read;
use app\repositories\traits\Update;
use app\repositories\traits\Delete;

class ProdutosRepository
{
    use Read,Create,Update,Delete;

    protected $connection;
    protected $tableName = ' produtos ';

    public function getAll(): array
    {
        $this->connection = new MySqlConnection(new MySqlConfig());
        $this->connection->connect();
        $this->select('x.id_produtos idProduto, x.fk_temporada fkTemporada, x.codigo	, x.nome, x.valor_uni valorUnit')               
            ->addJoins('inner','temporadas t', 'x.fk_temporada = t.id_temporada')
            ->where('t.ativo','1');
        $r = $this->getList(\app\entities\ProdutosEntity::class);
        $this->connection->disconnect();
        return $r;
    }

    public function getById(int $id): \app\entities\ProdutosEntity
    {
        $this->connection = new MySqlConnection(new MySqlConfig());
        $this->connection->connect();
        $this->select('x.id_produtos idProduto, x.fk_temporada fkTemporada, x.codigo	, x.nome, x.valor_uni valorUnit')               
            ->addJoins('inner','temporadas t', 'x.fk_temporada = t.id_temporada')
            ->where('x.id_produtos', $id)
            ->where('t.ativo','1');
        $r = $this->getFirst(\app\entities\ProdutosEntity::class);
        $this->connection->disconnect();
        return $r;
    }

    public function save(array $data): \app\entities\ProdutosEntity
    {
        $this->connection = new MySqlConnection(new MySqlConfig());
        $this->connection->connect();
        if(isset($data['fkTemporada'])){
            $data = $this->convertToBase($data);
        }
        if (empty($data['id_produtos'])) {
            $this->create($data);
            $data['id_produtos'] = $this->connection->getLastInsertId();
        } else {
            $this->update($data['id_produtos'], $data);
        }
        $this->connection->disconnect();
        return $this->getById($data['id_produtos']);
    }

    private function convertToBase(array $data): array
    {
        isset($data['idProduto']) ?$data['id_produto'] = $data['idProduto']:'';
        $data['fk_temporada'] = $data['fkTemporada'] ?? null;
        $data['codigo'] = $data['codigo'] ?? null;
        $data['nome'] = $data['nome'] ?? null;
        $data['valor_uni'] = $data['valorUni'] ?? null;
        return $data;
    }
}
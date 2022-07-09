<?php

namespace app\repositories;

use app\config\connection\MySqlConfig;
use app\repositories\connections\MySqlConnection;
use app\repositories\traits\Create;
use app\repositories\traits\Read;
use app\repositories\traits\Update;
use app\repositories\traits\Delete;

class MesaRepository
{
    use Read,Create,Update,Delete;

    protected $connection;
    protected $tableName = 'mesa';

    public function getAll(): array
    {
        $this->connection = new MySqlConnection(new MySqlConfig());
        $this->connection->connect();
        $this->select('x.id_mesa idMesa, x.codigo, x.descricao, x.ativo, p.id_pedido idPedido, sum(m.valor) valor ')
            ->addJoins('left','pedido p', 'p.fk_mesa = x.id_mesa and p.pago = false')
            ->addJoins('left','movimentacoes m','m.fk_pedido = p.id_pedido')
            ->groupBy('x.id_mesa');
        $r = $this->getList(\app\entities\MesasEntity::class, ['idMesa', 'codigo', 'descricao', 'ativo', 'idPedido', 'valor','']);
        $this->connection->disconnect();
        return $r;
    }
}
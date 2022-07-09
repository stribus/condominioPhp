<?php

namespace app\entities;

class MesasEntity
{
    public  $id_mesa;
    public  $codigo;
    public  $descricao;
    public  $ativo;
    public  $id_pedido;
    public  $valor;
    public  $cliente;
    
    public function __construct(  $id_mesa,  $codigo,  $descricao,  $ativo,  $id_pedido,  $valor,  $cliente)
    {
        $this->id_mesa = $id_mesa;
        $this->codigo = $codigo;
        $this->descricao = $descricao;
        $this->ativo = $ativo;
        $this->id_pedido = $id_pedido;
        $this->valor = $valor;
        $this->cliente = $cliente;
    }
    
    
}
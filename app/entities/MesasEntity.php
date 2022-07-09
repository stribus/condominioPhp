<?php

namespace app\entities;

class MesasEntity
{
    public  $idMesa;
    public  $codigo;
    public  $descricao;
    public  $ativo;
    public  $idPedido;
    public  $valor;
    public  $cliente;
    
    public function __construct(  $idMesa,  $codigo,  $descricao,  $ativo,  $idPedido,  $valor,  $cliente)
    {
        $this->idMesa = $idMesa;
        $this->codigo = $codigo;
        $this->descricao = $descricao;
        $this->ativo = $ativo;
        $this->idPedido = $idPedido;
        $this->valor = $valor;
        $this->cliente = $cliente;
    }
    
    
}
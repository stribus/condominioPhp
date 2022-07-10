<?php

namespace app\entities;

class MesasEntity implements \JsonSerializable
{
    private  $idMesa;
    private  $codigo;
    private  $descricao;
    private  $ativo;
    private  $idPedido;
    private  $valor;
    private  $cliente;
    
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
    
    public function __get($name)
    {
        return $this->$name;
    }

    public function jsonSerialize()
    {
        return [
            'idMesa' => $this->idMesa,
            'codigo' => $this->codigo,
            'descricao' => $this->descricao,
            'ativo' => $this->ativo,
            'idPedido' => $this->idPedido,
            'valor' => $this->valor,
            'cliente' => $this->cliente
        ];
    }

    public function __toString()
    {
        return json_encode($this->jsonSerialize());
    }    
}
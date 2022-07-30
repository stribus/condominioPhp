<?php

namespace app\entities;

class ProdutosEntity implements \JsonSerializable
{
    private $idProduto;
    private $fkTemporada;
    private $codigo;
    private $nome;
    private $valorUnit;
    
    
    public function __construct($idProduto=null, $fkTemporada=null,$codigo=null, $nome=null, $valorUnit=null)
    {
        $this->idProduto = $this->idProduto??$idProduto;
        $this->fkTemporada = $this->fkTemporada??$fkTemporada;
        $this->codigo = $this->codigo??$codigo;
        $this->nome = $this->nome??$nome;
        $this->valorUnit = $this->valorUnit??$valorUnit;
    }

    public function __get($name)
    {
        return $this->$name;
    }
  
    public function jsonSerialize()
    {
        return [
            'idProduto' => $this->idProduto,
            'fkTemporada' => $this->fkTemporada,
            'codigo' => $this->codigo,
            'nome' => $this->nome,
            'valorUnit' => $this->valorUnit
        ];
    }

    public function __toString()
    {
        return json_encode($this->jsonSerialize());
    }    

}
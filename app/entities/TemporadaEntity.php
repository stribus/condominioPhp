<?php

namespace app\entities;

class TemporadaEntity
{
    public $idTemporada;
    public $descricao;
    public $dataInicio;
    public $dataFim;
    public $ativo;

    public function __construct($idTemporada, $descricao, $dataInicio, $dataFim, $ativo)
    {
        $this->idTemporada = $this->idTemporada??$idTemporada;
        $this->descricao = $this->descricao??$descricao;
        $this->dataInicio = $this->dataInicio??$dataInicio;
        $this->dataFim = $this->dataFim??$dataFim;
        $this->ativo = $this->ativo??$ativo;
    }

    
}
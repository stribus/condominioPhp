<?php

namespace app\config;

use app\entities\TemporadaEntity;
use app\repositories\TemporadaRepository;

class TemporadaConfig implements Configuration
{
    
    public function __construct()
    {
        if (!isset($_SESSION['temporada'])) {
            $temp = new TemporadaRepository();
            $t = $temp->getTemporadaAtiva();
            $_SESSION['temporada'] = $t;
        }
    }

    public function getConfig():TemporadaEntity
    {
        $temporadaAtiva = $_SESSION['temporadaAtiva'];
        return $temporadaAtiva;
    }

    public static function getIdTemporada():int
    {
        if(!isset($_SESSION['temporada'])) {
            $temp = new TemporadaRepository();
            $t = $temp->getTemporadaAtiva();
            $_SESSION['temporada'] = $t;
        }
        $temporadaAtiva = $_SESSION['temporadaAtiva'];
        return $temporadaAtiva->idTemporada;
    }
}
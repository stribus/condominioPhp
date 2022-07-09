<?php

namespace app\controllers;

use app\controllers\traits\View;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

class MesasController
{
    use View;

    function main(Request $request, Response $response, $args)
    {
        $this->view('mesas', [
            'title' => 'Mesas',
            'menu' => 'mesas'
        ]);

        return $response;
    }

    function getAll(Request $request, Response $response, $args)
    {
        $mesas = new \app\repositories\MesaRepository();
        $mesas = $mesas->getAll();
        $retorno = ['data' => $mesas];
        $response->getBody()->write(json_encode($retorno));
        return $response->withHeader('Content-Type', 'application/json');
    }
}
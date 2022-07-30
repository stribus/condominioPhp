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

    function getById(Request $request, Response $response, $args)
    {
        $mesa = new \app\repositories\MesaRepository();
        $mesa = $mesa->getById($args['id']);
        
        $response->getBody()->write(json_encode($mesa));
        return $response->withHeader('Content-Type', 'application/json');
    }

    function save(Request $request, Response $response, $args)
    {
        $mesa = new \app\repositories\MesaRepository();
        $mesa = $mesa->save($request->getParsedBody());
        $retorno = ['data' => $mesa];
        $response->getBody()->write(json_encode($retorno));
        return $response->withHeader('Content-Type', 'application/json');
    }

    function delete(Request $request, Response $response, $args)
    {
        $mesa = new \app\repositories\MesaRepository();
        $mesa = $mesa->delete($args['id']);
        $retorno = ['data' => $mesa];
        $response->getBody()->write(json_encode($retorno));
        return $response->withHeader('Content-Type', 'application/json');
    }
}
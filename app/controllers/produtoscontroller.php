<?php

namespace app\controllers;

use app\config\TemporadaConfig;
use app\controllers\traits\View;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

class produtoscontroller
{
    use View;

    function main(Request $request, Response $response, $args)
    {
        $this->view('produtos', [
            'title' => 'Produtos',
            'menu' => 'produtos'
        ]);

        return $response;
    }

    function getAll(Request $request, Response $response, $args)
    {
        $produtos = new \app\repositories\produtosRepository();
        $produtos = $produtos->getAll();
        $retorno = ['data' => $produtos];
        $response->getBody()->write(json_encode($retorno));
        return $response->withHeader('Content-Type', 'application/json');
    }

    function getById(Request $request, Response $response, $args)
    {
        $produto = new \app\repositories\produtosRepository();
        $produto = $produto->getById($args['id']);
        $response->getBody()->write(json_encode($produto));

        return $response->withHeader('Content-Type', 'application/json');
    }

    function  create(Request $request, Response $response, $args)
    {
        $produtos = new \app\repositories\produtosRepository();
        $produto =$request->getParsedBody();
        $produto['fkTemporada'] = TemporadaConfig::getIdTemporada();
        $produto = $produtos->save($produto);
        $response->getBody()->write(json_encode($produto));
        return $response->withHeader('Content-Type', 'application/json');
    }

    function update(Request $request, Response $response, $args)
    {
        $produto = new \app\repositories\produtosRepository();
        $produto = $produto->save($request->getParsedBody());
        $response->getBody()->write(json_encode($produto));
        return $response->withHeader('Content-Type', 'application/json');
    }

    function delete(Request $request, Response $response, $args)
    {
        $produto = new \app\repositories\produtosRepository();
        $produto = $produto->delete($args['id']);
        $response->getBody()->write(json_encode($produto));
        return $response->withHeader('Content-Type', 'application/json');
    }
}
<?php

namespace app\controllers;

use app\config\connection\MySqlConfig;
use app\repository\connections\MySqlConnection;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

class testes
{
    public function main(Request $request,Response $response, $args)
    {
        $c = new MySqlConnection(new MySqlConfig());
        $c->connect();

        $r = $c->query("select * from test.mock_data md where quantidade = ? ", [9])->getAll();
        
        $response->getBody()->write(json_encode($r));

        return $response;
    }

}
<?php

namespace app\controllers;

use app\controllers\traits\View;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use app\config\connection\MySqlConfig;
use app\repositories\connections\MySqlConnection;

class testes
{
    use View;

    public function main(Request $request,Response $response, $args)
    {
        // $c = new MySqlConnection(new MySqlConfig());
        // $c->connect();

        // $r = $c->query("select * from test.mock_data md where quantidade = ? ", [9])->getAll();
        
        // $response->getBody()->write(json_encode($r));

        $this->view('mesas', [
            'title' => 'Teste',
            'content' => 'Teste'
        ]);

        return $response;
    }

}
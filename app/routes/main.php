<?php



// $app->get('/', function (Request $request, Response $response, $args) {
    
//     $response->getBody()->write("Hello world!");
//     return $response;
// });


$app->get('/','app\controllers\Testes:main');
<?php



// $app->get('/', function (Request $request, Response $response, $args) {
    
//     $response->getBody()->write("Hello world!");
//     return $response;
// });


//$app->get('/','app\controllers\Testes:main');

// $app->get('/',fn($request, $response, $args) => $response->withHeader('Location', '/mesas')->withStatus(302));
$app->redirect('/','/mesas');
$app->get('/assetsphp/{file:.*}', 'app\controllers\AssetsController:get');
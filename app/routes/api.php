<?php


$app->get('/api/mesas', 'app\controllers\MesasController:getAll');
$app->get('/api/mesas/{id}', 'app\controllers\MesasController:getById');
$app->post('/api/mesas', 'app\controllers\MesasController:create');
$app->put('/api/mesas/{id}', 'app\controllers\MesasController:update');
$app->delete('/api/mesas/{id}', 'app\controllers\MesasController:delete');

$app->get('/api/produtos', 'app\controllers\ProdutosController:getAll');
$app->get('/api/produtos/{id}', 'app\controllers\ProdutosController:getById');
$app->post('/api/produtos', 'app\controllers\ProdutosController:create');
$app->put('/api/produtos/{id}', 'app\controllers\ProdutosController:update');
$app->delete('/api/produtos/{id}', 'app\controllers\ProdutosController:delete');

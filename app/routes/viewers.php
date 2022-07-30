<?php


$app->get('/mesas', 'app\controllers\MesasController:main');
$app->get('/produtos', 'app\controllers\ProdutosController:main');
<?php



//use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Slim\Exception\HttpNotFoundException;
use Slim\Exception\HttpMethodNotAllowedException;
use Slim\Psr7\Response;

// require __DIR__.'/vendor/slim/slim/Slim/Exception/HttpException.php';
// require __DIR__.'/vendor/slim/slim/Slim/Exception/HttpSpecializedException.php';
// require __DIR__.'/vendor/slim/slim/Slim/Exception/HttpNotFoundException.php';

require __DIR__ . '/vendor/autoload.php'; 

$app = AppFactory::create();
//$app->setBasePath("/"); 

$errorMiddleware = $app->addErrorMiddleware(true, true, true);

// Set the Not Found Handler
$errorMiddleware->setErrorHandler(
    HttpNotFoundException::class,
    function (Request $request, Throwable $exception, bool $displayErrorDetails) {
        $response = new Response();
        $response->getBody()->write('404 NOT FOUND');

        return $response->withStatus(404);
    });

// Set the Not Allowed Handler
$errorMiddleware->setErrorHandler(
    HttpMethodNotAllowedException::class,
    function (Request $request, Throwable $exception, bool $displayErrorDetails) {
        $response = new Response();
        $response->getBody()->write('405 NOT ALLOWED');

        return $response->withStatus(405);
    });

$app->get('/hello/{name}', function (Request $request, Response $response, array $args) {
    $name = $args['name'];
    $response->getBody()->write("Hello, $name");
    return $response;
});

require_once(__DIR__ .'/app/routes/main.php');
require_once(__DIR__ .'/app/routes/api.php');
require_once(__DIR__ .'/app/routes/viewers.php');



$app->run();
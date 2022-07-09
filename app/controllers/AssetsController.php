<?php

namespace app\controllers;

use Slim\Psr7\Request;
use Slim\Psr7\Response;

class AssetsController
{
    private $mimePermitidos = [ 'text/html', 'text/css', 'text/javascript', 'application/javascript', 'application/json', 'text/plain' ];

    public function get(Request $request,Response $response,$args)
    {
        $file = $request->getUri()->getPath();
        $file = str_replace('/assetsphp/', '', $file);
        $file = str_replace('/../', '', $file);
        $file = __DIR__ . '/../../vendor/' . $file;
        if (file_exists($file)) {
            $mime = mime_content_type($file);
            if (in_array($mime, $this->mimePermitidos)) {
                $response = $response->withHeader('Content-Type', mime_content_type($file));
                $response = $response->withHeader('Content-Length', filesize($file));
                $response = $response->withHeader('Content-Disposition', 'attachment; filename="'.basename($file).'"');
                $response = $response->withBody(new \Slim\Psr7\Stream(fopen($file, 'r')));
                return $response;
            }
        } 
        $response = $response->withStatus(404);
        
        return $response;
    }
}
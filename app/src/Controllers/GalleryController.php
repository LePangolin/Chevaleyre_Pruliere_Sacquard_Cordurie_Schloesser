<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;

use App\Services\GalleryService;

class GalleryController
{

    public function __construct(GalleryService $galleryService, Twig $twig)
    {
        $this->galleryService = $galleryService;
        $this->twig = $twig;
    }

    public function create(Request $request, Response $response, array $args): Response
    {
        return $this->twig->render($response, 'createGallery.html.twig', [
            
        ]);
    }

    public function createGallery(Request $request, Response $response, array $args): Response
    {
        $data = $request->getParsedBody();
        $tags = json_decode($data["tags"]);
        $bool = $this->galleryService->create($data["name"], $data["description"], 2, $data["statut"], $tags);
        if($bool){
            return $response->withHeader('Location', '/')->withStatus(302);
        }else{
            return $response->withHeader('Location', '/create')->withStatus(302);
        }
    }

}
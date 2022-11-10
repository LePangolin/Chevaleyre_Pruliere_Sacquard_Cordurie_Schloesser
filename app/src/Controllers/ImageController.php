<?php

namespace App\Controllers;

use App\Services\ImageService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;

class ImageController
{

    private Twig $twig;
    private ImageService $imageService;

    public function __construct(ImageService $imageService, Twig $twig)
    {
        $this->imageService = $imageService;
        $this->twig = $twig;
    }

    public function create(Request $request, Response $response, array $args): Response
    {
        if(isset($_SESSION["user"])){
            return $this->twig->render($response, 'addImage.html.twig', [
                "title" => "Ajouter une image",
                "user" => $_SESSION["user"]
            ]);
        }else{
            return $response->withHeader('Location', '/auth')->withStatus(302);
        }
    }

}
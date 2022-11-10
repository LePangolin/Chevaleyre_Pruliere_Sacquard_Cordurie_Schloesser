<?php

namespace App\Controllers;

use App\Services\ImageService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;

class ImageControllerController
{

    private Twig $twig;
    private ImageService $imageService;

    public function __construct(ImageService $imageService, Twig $twig)
    {
        $this->imageService = $imageService;
        $this->twig = $twig;
    }

    public function uploadImage(Request $request, Response $response, array $args): Response
    {
        $data = $request->getParsedBody();
        //$idGallery = $args['id'];

        if(!empty($_FILES['uploadImage']['name']))
        {
            $this->uploadImage($data['title'], $data['description']);
        }

        //return $response->withHeader('Location', "/gallery/.{$idGallery}")->withStatus(302);
        return $response->withHeader('Location', "/")->withStatus(302);
    }

}
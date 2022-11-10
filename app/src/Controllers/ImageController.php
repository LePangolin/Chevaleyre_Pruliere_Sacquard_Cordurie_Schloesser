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

}
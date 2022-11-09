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

        $bool = $this->galleryService->create($data["name"], $data["description"], 2, $data["statut"]);
        if($bool){
            $resp = array(
                'status' => 'success',
                'message' => 'Form sent'
            );
        }else{
            $resp = array(
                'status' => 'error',
                'message' => 'A problem occured'
            );
        }
        $response->getBody()->write(json_encode($resp));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function searchGalleries(Request $rq, Response $rs, array $args): Response
    {
        $search = "";
        if (isset($_POST['searchBar'])) {
            $search = $_POST['searchBar'];
        }
        $galleriesObjects = $this->galleryService->findGalleryByName($search);

        $galleriesArray = [];
        foreach ($galleriesObjects as $gallery) {
            $galleriesArray[] =  [
                'id' => $gallery->getId(),
                'name' => $gallery->getName(),
                'description' => $gallery->getDescription(),
                'nb_pictures' => $gallery->getNbPictures(),
                'public' => $gallery->getPublic()
            ];
        }

        $tags = [];        

       
        return $this->twig->render($rs, 'searchPage.html.twig', [
            'title' => 'Recherche',
            'search' => $search,
            'galleries' => $galleriesArray,
            'tags' => $tags
        ]);
    }

}
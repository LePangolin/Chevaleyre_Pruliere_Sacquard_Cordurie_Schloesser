<?php 

namespace App\Controllers;

use App\Services\GalleryService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;

class GalleryController {
    private Twig $twig;
    private GalleryService $gallery_service;

    public function __construct(Twig $twig, GalleryService $gallery_service) {
        $this->twig = $twig;
        $this->gallery_service = $gallery_service;
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
    
    public function displayGallery(Request $request, Response $response, $args): Response 
    {
        // On récupère la galerie d'id args['id']
        $a = $this->gallery_service->getGallery($args['id']);
        $gallery = [
            'id' => $a->getId(),
            'title' => $a->getName(),
        ];

        // On vérifie si l'id de la session correspond à celui du créateur de la galerie
        $is_author = false;
        if (isset($_SESSION['user'])) {
            if ($a->getId() == $_SESSION['user']->getId()) {
                $is_author = true;
            }
        }        
        
        // On récupère les images de la galerie d'id args['id']
        $b = $this->gallery_service->getPictures($args['id']);
        $pictures = []; 
        foreach($b as $picture) {
            array_push($pictures, ['link' => $picture->getLink(), 'desc' => $picture->getDescription()]);
        }

        return $this->twig->render($response, 'gallery.html.twig', [
            'title' => 'Gallery',
            'gallery' => $gallery,
            'is_author' => $is_author,
            'pictures' => $pictures,
        ]);
    }
}
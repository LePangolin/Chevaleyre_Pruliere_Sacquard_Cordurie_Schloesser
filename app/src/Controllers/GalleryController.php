<?php 

namespace App\Controllers;

use App\Services\GalleryService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;

class GalleryController
{

    private Twig $twig;
    private GalleryService $galleryService;

    public function __construct(GalleryService $galleryService, Twig $twig) {
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
    
    public function displayGallery(Request $request, Response $response, $args): Response 
    {
        // On récupère la galerie d'id args['id']
        $a = $this->galleryService->getGallery($args['id']);
        $gallery = [
            'id' => $a->getId(),
            'title' => $a->getName(),
        ];
        // On vérifie si l'id de la session correspond à celui du créateur de la galerie
        $is_author = false;
        if (isset($_SESSION['user'])) {
            if ($this->galleryService->getCreator($args['id'])[0]->getIdUser() == $_SESSION['user']->getId()) {
                $is_author = true;
            }
        }        
        
        // On récupère les images de la galerie d'id args['id']
        $b = $this->galleryService->getPictures($args['id']);
        $pictures = []; 
        foreach($b as $picture) {
            array_push($pictures, ['link' => $picture->getLink(), 'descr' => $picture->getDescr()]);
        }

        return $this->twig->render($response, 'gallery.html.twig', [
            'title' => 'Gallery',
            'gallery' => $gallery,
            'is_author' => $is_author,
            'pictures' => $pictures,
        ]);
    }


    public function displayPublicGalleries(Request $request, Response $response, array $args): Response
    {
        $offsetPublic = $request->getQueryParams()['offsetPublic'];
        $galleries = $this->galleryService->listPublicGalleries($offsetPublic);
        $tabImg = array();

        foreach ($galleries as $gallery) {
            $random = rand(1, $gallery->getNbPictures());
            $idGallery = $gallery->getId();
            $tabImg[$idGallery] = $this->galleryService->getPictureById($idGallery, $random)->getLink();
        }

        return $this->twig->render($response, 'index.html.twig', [
            'listPublicGalleries' => $galleries,
            'tabImg' => $tabImg
        ]);
    }

}
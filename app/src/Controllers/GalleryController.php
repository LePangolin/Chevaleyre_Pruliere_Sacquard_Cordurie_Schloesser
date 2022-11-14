<?php 

namespace App\Controllers;

use App\Services\GalleryService;
use App\Services\ImageService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;

class GalleryController
{

    private Twig $twig;
    private GalleryService $galleryService;
    private ImageService $imageService;

    public function __construct(GalleryService $galleryService, ImageService $imageService, Twig $twig) {
        $this->galleryService = $galleryService;
        $this->imageService = $imageService;
        $this->twig = $twig;
    }

    public function create(Request $request, Response $response, array $args): Response
    {
        if(isset($_SESSION["user"])){
            return $this->twig->render($response, 'createGallery.html.twig', [
                "title" => "Création d'une galerie",
                "user" => $_SESSION["user"]
            ]);
        }else{
            return $response->withHeader('Location', '/auth')->withStatus(302);
        }
    }

    public function createGallery(Request $request, Response $response, array $args): Response
    {
        $data = $request->getParsedBody();
        if(!empty($data["tags"])){
            $tags = json_decode($data["tags"]);
        }else{
            $tags = array();
        }

        if(!empty($data["users"]) && $data["statut"] == 0){
            $users = json_decode($data["users"]);
        }else{
            $users = array();
        }

        $idUser = $_SESSION["user"]->getId();
        $bool = $this->galleryService->create($data["name"], $data["description"], 0, $data["statut"], $idUser, $tags, $users);
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
        if (is_null($a)) {
            return $response->withHeader('Location', '/')->withStatus(302);
        }
        $gallery = $this->galleryService->getGalleryInfo($args['id']);
        if (!$a->getPublic()) {
            if (!isset($_SESSION['user'])) {
                return $response->withHeader('Location', '/')->withStatus(302);
            } else {
                $id_current_user = $_SESSION['user']->getId();
                $id_creator = $this->galleryService->getCreator($args['id'])[0]->getIdUser();
                $id_users = $this->galleryService->getListUser($args['id']);
                if ($id_current_user != $id_creator && !in_array($id_current_user, $id_users)) {
                    return $response->withHeader('Location', '/')->withStatus(302);
                }
            }
        }

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
        foreach($b as $picture)
        {
            array_push($pictures, $this->imageService->getPictureInfo($picture->getId()));
        }
        $userSession = $_SESSION["user"] ?? null;
        
        return $this->twig->render($response, 'gallery.html.twig', [
            'title' => 'Gallery',
            "user" => $userSession,
            'gallery' => $gallery,
            'is_author' => $is_author,
            'pictures' => $pictures
        ]);
    }

    public function searchGalleries(Request $request, Response $response, array $args): Response
    {
        $data = $request->getParsedBody();

        $tags = [];
        if (!empty($data["tags"])) {
            $tags = json_decode($data["tags"]);
        }

        $nameSearch = "";
        if (!empty($data["searchBar"])) {
            $nameSearch = $data["searchBar"];
        }

        $galleriesObjects = [];
        if (empty($tags)) {
            $galleriesObjects = $this->galleryService->findGalleriesByName($nameSearch);
        } else {
            $galleriesObjects = $this->galleryService->findGalleriesByNameAndTags($nameSearch, $tags);
        }

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
       
        $userSession = $_SESSION["user"] ?? null;

        return $this->twig->render($response, 'searchPage.html.twig', [
            'title' => 'Recherche',
            "user" => $userSession,
            'search' => $nameSearch,
            'galleries' => $galleriesArray,
            'tags' => $tags
        ]);
    }

    public function displayPublicGalleries(Request $request, Response $response, array $args): Response
    {
        if(isset($request->getQueryParams()['offsetPublic']))
        {
            $offsetPublic = $request->getQueryParams()['offsetPublic'];
        } else {
            $offsetPublic = 0;
        }

        $galleries = $this->galleryService->listPublicGalleries($offsetPublic);
        $tabImg = array();

        foreach ($galleries as $gallery)
        {
            $random = rand(1, $gallery->getNbPictures());
            $idGallery = $gallery->getId();
            if ($gallery->getNbPictures() > 0 && $gallery->getNbPictures() !== null) {
                $tabImg[$idGallery] = $this->galleryService->getPictureById($idGallery, $random)->getLink();
            } else {
                $tabImg[$idGallery] = null;
            }
        }

        $userSession = $_SESSION["user"] ?? null;

        return $this->twig->render($response, 'index.html.twig', [
            "user" => $userSession,
            'listPublicGalleries' => $galleries,
            'tabImg' => $tabImg
        ]);
    }

    public function displayPrivateGalleries(Request $request, Response $response, array $args): Response
    {
        if(isset($_SESSION['user']))
        {
            $idUser = $_SESSION['user']->getId();

            if(isset($request->getQueryParams()['offsetPrivate'])){
                $offsetPrivate = $request->getQueryParams()['offsetPrivate'];
            } else {
                $offsetPrivate = 0;
            }

            $galleries = $this->galleryService->listPrivateGalleries($idUser, $offsetPrivate);
            $tabImg = array();

            foreach ($galleries as $gallery)
            {
                $random = rand(1, $gallery->getNbPictures());
                $idGallery = $gallery->getId();
                if ($gallery->getNbPictures() > 0 && $gallery->getNbPictures() !== null) {
                    $tabImg[$idGallery] = $this->galleryService->getPictureById($idGallery, $random)->getLink();
                } else {
                    $tabImg[$idGallery] = null;
                }
            }
            
            $userSession = $_SESSION["user"] ?? null;

            return $this->twig->render($response, 'index.html.twig', [
                'listPrivateGalleries' => $galleries,
                'tabImgPrivate' => $tabImg,
                'user' => $userSession
            ]);
        }

        return $this->twig->render($response, 'index.html.twig', [ 'user' => $_SESSION["user"] ]);

    }

    public function diaplayEditGallery(Request $request, Response $response, array $args): Response
    {
        if(isset($_SESSION['user']))
        {
            $gallery = $this->galleryService->getGalleryInfo($args['id']);

            if(!empty($gallery))
            {
                // On vérifie si l'id de la session correspond à celui du créateur de la galerie

                if ($this->galleryService->getCreator($args['id'])[0]->getIdUser() == $_SESSION['user']->getId()) {
                    $is_author = true;
                } else {
                    $is_author = false;
                }

                if($is_author)
                {
                    $userSession = $_SESSION["user"] ?? null;

                    return $this->twig->render($response, 'editGallery.html.twig', [
                        'title' => 'Edit Gallery',
                        'gallery' => $gallery,
                        'user' => $userSession
                    ]);
                }else{
                    return $response->withHeader('Location', '/')->withStatus(302);
                }
            }else{
                return $response->withHeader('Location', "/gallery/".$args['id'])->withStatus(302);
            }
        }

        return $response->withHeader('Location', '/auth')->withStatus(302);
    }

    public function editGallery(Request $request, Response $response, array $args): Response
    {
        $data = $request->getParsedBody();



        if(!empty($data["tags"])){
            $tags = json_decode($data["tags"]);
        }else{
            $tags = array();
        }

        if(!empty($data["users"]) && $data["statut"] == 0){
            $users = json_decode($data["users"]);
        }else{
            $users = array();
        }

        $this->galleryService->editGallery($args['id'], $data["name"], $data["description"], $data["statut"], $tags, $users);

        return $response->withHeader('Location', '/gallery/'.$args['id'])->withStatus(302);

    }

    public function deleteGallery(Request $request, Response $response, $args): Response
    {
        $this->galleryService->deleteGallery();
        return $response->withHeader('Location', '/')->withStatus(302);
    }

}
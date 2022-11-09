<?php 

namespace App\Services;

use App\Models\Gallery;
use App\Models\Tag;
use App\Models\GalleryToTag;
use Doctrine\ORM\EntityManager;
use Psr\Log\LoggerInterface;

class GalleryService
{

    public function __construct(EntityManager $em, LoggerInterface $logger){
        $this->em = $em;
        $this->logger = $logger;
    }

    public function create(string $name, string $descr, int $nb_pictures, int $public, string $tags){
        try{
            $gallery = new Gallery(filter_var($name), filter_var($descr), filter_var($nb_pictures), filter_var($public));
            $this->em->persist($gallery);
            $this->em->flush();
            $this->logger->info("Gallery $name has been created");
            // foreach($tags as $tag){
            //     $idsT = [];
                $tag = new Tag(filter_var($tags));
                $this->em->persist($tag);
                $this->em->flush();
                $this->logger->info("Tag" . $tag->getTag() . "has been created");
                // array_push($idsT, $tag->getId());
            // }
            $idG = $gallery->getId();
            $idT = $tag->getId();
            // foreach($idsT as $id){
                $link = new GalleryToTag($idG,$idT);
                $this->em->persist($link);
                $this->em->flush();
                $this->logger->info("Link between gallery $name and tag number" . $tag->getTag() . "has been created");
            // }
            return true;
        }catch(\Exception $e){
            $this->logger->error("Erreur lors de la création de la galerie $name : " . $e->getMessage());
            return false;
        }
    }
}
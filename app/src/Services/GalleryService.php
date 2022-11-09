<?php

namespace App\Services;

use App\Models\Gallery;
use App\Models\GalleryToPicture;
use App\Models\Picture;
use Doctrine\ORM\EntityManager;
use Psr\Log\LoggerInterface;

final class GalleryService {
    private EntityManager $em;

    public function __construct(EntityManager $em, LoggerInterface $logger) {
        $this->em = $em;
        $this->logger = $logger;
    }

    public function create(string $name, string $descr, int $nb_pictures, int $public){
        $gallery = new Gallery($name, $descr, $nb_pictures, $public);
        $this->em->persist($gallery);
        $this->em->flush();
        $this->logger->info("Gallery $name has been created");
        return true;
    }
    
    public function getGallery($id_gallery)
    {
        $gallery = $this->em->getRepository(Gallery::class)->find($id_gallery);
        return $gallery;
    }

    public function getPictures($id_gallery) 
    {
        $join = $this->em->getRepository(GalleryToPicture::class)->find($id_gallery);
        $pictures = [];
        foreach ($join as $id_picture) {
            $picture = $this->em->getRepository(Picture::class)->find($id_picture);
            array_push($pictures, $picture);
        }
        return $pictures;
    }
}
<?php 

namespace App\Services;

use App\Models\Gallery;
use Doctrine\ORM\EntityManager;
use Psr\Log\LoggerInterface;
use App\Models\Picture;
use App\Models\GalleryToPicture;

class GalleryService
{

    public function __construct(EntityManager $em, LoggerInterface $logger)
    {
        $this->em = $em;
        $this->logger = $logger;
    }

    public function create(string $name, string $descr, int $nb_pictures, int $public)
    {
        $gallery = new Gallery($name, $descr, $nb_pictures, $public);
        $this->em->persist($gallery);
        $this->em->flush();
        $this->logger->info("Gallery $name has been created");
        return true;
    }

    public function listPublicGalleries()
    {
        $galleries = $this->em->getRepository(Gallery::class)->findBy(['public' => 1]);
        return $galleries;
    }

    public function getPictureById($id, $random)
    {
        $galToPicture = $this->em->getRepository(GalleryToPicture::class)->findBy(['id_gallery' => $id]);
        $index = $galToPicture[$random-1];
        $picture = $this->em->getRepository(Picture::class)->find(['id' => $index->getIdPicture()]);
        return $picture;
    }

}
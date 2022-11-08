<?php 

namespace App\Services;

use App\Models\Gallery;
use Doctrine\ORM\EntityManager;
use Psr\Log\LoggerInterface;

class GalleryService
{

    public function __construct(EntityManager $em, LoggerInterface $logger){
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
}
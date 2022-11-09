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

    /* Retourne jusqu'a 10 galeries (les plus recemment crees) dont le nom contient $name */
    public function findGalleryByName(string $name){
        $galleries = $this->em->getRepository(Gallery::class)->createQueryBuilder('q')
            ->where('q.name LIKE :name')
            ->setParameter('name', '%'.$name.'%')
            ->orderBy('q.created_at', 'DESC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
        return $galleries;
    }

}
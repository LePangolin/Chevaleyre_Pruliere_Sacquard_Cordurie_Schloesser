<?php

namespace App\Services;

use App\Models\Gallery;
use App\Models\Tag;
use App\Models\GalleryToTag;
use Doctrine\ORM\EntityManager;
use Psr\Log\LoggerInterface;
use App\Models\Picture;
use App\Models\GalleryToPicture;

final class GalleryService {

    private EntityManager $em;

    public function __construct(EntityManager $em, LoggerInterface $logger) {
        $this->em = $em;
        $this->logger = $logger;
    }

    public function create(string $name, string $descr, int $nb_pictures, int $public, array $tags){
        try{
            $gallery = new Gallery(filter_var($name), filter_var($descr), filter_var($nb_pictures), filter_var($public));
            $this->em->persist($gallery);
            $this->em->flush();
            $this->logger->info("Gallery $name has been created");
            $idsT = [];
            foreach($tags as $tag){
                $tagg = new Tag(filter_var($tag));
                $this->em->persist($tagg);
                $this->em->flush();
                $this->logger->info("Tag" . $tagg->getTag() . "has been created");
                array_push($idsT, $tagg->getId());
            }
            $idG = $gallery->getId();
            foreach($idsT as $id){
                $link = new GalleryToTag($idG,$id);
                $this->em->persist($link);
                $this->em->flush();
                $this->logger->info("Link between gallery $name and tag" . $tagg->getTag() . "has been created");
            }
            return true;
        }catch(\Exception $e){
            $this->logger->error("Erreur lors de la création de la galerie $name : " . $e->getMessage());
            return false;
        }
    }

    /* Retourne jusqu'a 10 galeries (les plus recntes) dont le nom contient $name */
    public function findGalleriesByName(string $name, array $tags = []){ 
        $galleries = $this->em->getRepository(Gallery::class)->createQueryBuilder('q')
            ->where('q.name LIKE :name')
            ->setParameter('name', '%'.$name.'%') 
            ->orderBy('q.created_at', 'DESC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();

        return $galleries;
    }

    /* Retourne jusqu'a 10 galeries (les plus recntes) dont le nom contient $name */
    public function findGalleriesByNameAndTags(string $name, array $tags = []){ 
        $tagsIDs = [];
        foreach($tags as $tag){
            $tag = $this->em->getRepository(Tag::class)->findOneBy(['tag' => $tag]);
            if($tag){
                array_push($tagsIDs, $tag->getId());
            }
        }

        $galleries = $this->em->getRepository(Gallery::class)->createQueryBuilder('q')
            ->where('q.name LIKE :name')
            ->setParameter('name', '%'.$name.'%') 
            ->orderBy('q.created_at', 'DESC')
            ->getQuery()
            ->getResult();

        $galleries = array_filter($galleries, function($gallery) use ($tagsIDs) {
            $galleryTags = $this->em->getRepository(GalleryToTag::class)->findBy(['id_gallery' => $gallery->getId()]);
            $galleryTags = array_map(function($galleryTag) {
                return $galleryTag->getIdTag();
            }, $galleryTags);
            return count(array_intersect($galleryTags, $tagsIDs)) > 0;
        });

        return array_slice($galleries, 0, 10);
    }
    
    public function getGallery($id_gallery)
    {
        $gallery = $this->em->getRepository(Gallery::class)->find($id_gallery);
        return $gallery;
    }

    public function getPictures($id_gallery) 
    {
        $join = $this->em->getRepository(GalleryToPicture::class)->findBy(array('id_gallery' => $id_gallery));
        $pictures = [];
        foreach ($join as $ids) {
            $picture = $this->em->getRepository(Picture::class)->find($ids->getIdPicture());
            array_push($pictures, $picture);
        }
        return $pictures;

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
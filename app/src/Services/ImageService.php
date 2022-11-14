<?php

namespace App\Services;

use App\Models\Gallery;
use App\Models\Tag;
use App\Models\Picture;
use App\Models\PictureToTag;
use App\Models\GalleryToPicture;
use Doctrine\ORM\EntityManager;
use GMP;
use Psr\Log\LoggerInterface;


final class ImageService
{

    private EntityManager $em;

    public function __construct(EntityManager $em, LoggerInterface $logger, GalleryService $gs)
    {
        $this->em = $em;
        $this->logger = $logger;
        $this->gs = $gs;
    }

    public function uploadImage($name, $descr, $idGallery, array $tags)
    {
        try {
            $pic = new Picture(filter_var($name), filter_var($descr));
            $this->em->persist($pic);
            $this->em->flush();

            $types = [".jpg", ".png", ".JPG", ".PNG"];
            if (in_array(substr($_FILES['uploadImage']['name'], -4), $types)) {
                $extension = substr($_FILES['uploadImage']['name'], -3);
                move_uploaded_file($_FILES['uploadImage']['tmp_name'], __DIR__ . "/../../public/img/img_{$pic->getId()}.{$extension}");
                $pic->setLink("/img/img_{$pic->getId()}.{$extension}");
                $this->em->persist($pic);
                $this->em->flush();
                $this->logger->info("New image" . $name . " added");
            }
            $idPic = $pic->getId();
            $gToP = new GalleryToPicture($idGallery,$idPic);
            $this->em->persist($gToP);
            $this->em->flush();
            $this->logger->info("Link between image " . $name . "and gallery number " . $idGallery . " has been created");
            $currentGal = $this->gs->getGalleryById($idGallery);
            $currentGal->setNbPictures($currentGal->getNbPictures()+1);
            $this->em->persist($currentGal);
            $this->em->flush();
            $idsT = [];
            foreach($tags as $tag){
                $tagg = new Tag(filter_var($tag));
                $this->em->persist($tagg);
                $this->em->flush();
                $this->logger->info("Tag " . $tagg->getTag() . " has been created");
                array_push($idsT, $tagg->getId());
            }
            foreach($idsT as $id){
                $linkTag = new PictureToTag($idPic,$id);
                $this->em->persist($linkTag);
                $this->em->flush();
                $this->logger->info("Link between image $name and tag " . $tagg->getTag() . " has been created");
            }
        } catch (\Exception $e) {
            $this->logger->error("Error while uploading image: " . $e->getMessage());
            return false;
        }
    }

}

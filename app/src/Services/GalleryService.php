<?php

namespace App\Services;

use App\Models\Gallery;
use App\Models\Tag;
use App\Models\Picture;
use App\Models\GalleryToTag;
use App\Models\GalleryToPicture;
use App\Models\UserToGallery;
use App\Models\UserAccess;
use App\Services\UserService;
use Doctrine\ORM\EntityManager;
use GMP;
use Psr\Log\LoggerInterface;


final class GalleryService {

    private EntityManager $em;

    public function __construct(EntityManager $em, LoggerInterface $logger, UserService $us) {
        $this->em = $em;
        $this->logger = $logger;
        $this->us = $us;
    }

    public function create(string $name, string $descr, int $nb_pictures, int $public, int $idUser, array $tags, array $users){
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
                $this->logger->info("Tag " . $tagg->getTag() . " has been created");
                array_push($idsT, $tagg->getId());
            }
            $idG = $gallery->getId();
            foreach($idsT as $id){
                $linkTag = new GalleryToTag($idG,$id);
                $this->em->persist($linkTag);
                $this->em->flush();
                $this->logger->info("Link between gallery $name and tag " . $tagg->getTag() . " has been created");
            }
            $linkUser = new UserToGallery($idUser,$idG);
            $this->em->persist($linkUser);
            $this->em->flush();
            $this->logger->info("Link between gallery $name and user number " . $idUser . " has been created");
            if($gallery->getPublic() == 0){
                foreach($users as $user){
                    $userAdd = $this->us->getUserByUsername($user);
                    if($userAdd !== null){
                        $linkUserPrivate = new UserAccess($userAdd->getId(),$idG);
                        $this->em->persist($linkUserPrivate);
                        $this->em->flush();
                        $this->logger->info("Link between private gallery $name and user " . $userAdd->getUsername() . " has been created");
                    }
                }
            }
            return true;
        }catch(\Exception $e){
            $this->logger->error("Erreur lors de la création de la galerie $name : " . $e->getMessage());
            return false;
        }
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

    public function listPublicGalleries($offsetPublic = 0)
    {
        $galleries = $this->em->getRepository(Gallery::class)->findBy(['public' => 1], ['id' => 'DESC'], 10, $offsetPublic);
        return $galleries;
    }

    public function listPrivateGalleries($idUser, $offsetPrivate = 0)
    {
        $userToGalleries = $this->em->getRepository(UserToGallery::class)->findBy(['id_user' => $idUser]);
        $userGalleries = [];
        foreach($userToGalleries as $userToGallery){
            $userGalleries[] = $this->em->getRepository(Gallery::class)->findOneBy(['id' => $userToGallery->getGalleryId()]);
        }

        $userAccess = $this->em->getRepository(UserAccess::class)->findBy(['id_user' => $idUser]);
        $userAccessGalleries = [];
        foreach($userAccess as $access){
            $userAccessGalleries[] = $this->em->getRepository(Gallery::class)->findOneBy(['id' => $access->getGalleryId()]);
        }

        $PrivateGalleries = array_merge($userGalleries, $userAccessGalleries);

        $PrivateGalleries = array_slice($PrivateGalleries, $offsetPrivate, 10);

        return $PrivateGalleries;
    }

    public function getPictureById($id, $random)
    {
        try {
            $galToPicture = $this->em->getRepository(GalleryToPicture::class)->findBy(['id_gallery' => $id]);
            $index = $galToPicture[$random-1];
            $picture = $this->em->getRepository(Picture::class)->find(['id' => $index->getIdPicture()]);
            return $picture;
        } catch (\Exception $e) {
            $this->logger->error("Erreur lors de la récupération de la galerie ou des photos $id : " . $e->getMessage());
            return null;
        }

    }

    public function getCreator($id_gallery)
    {
        $creator = $this->em->getRepository(UserToGallery::class)->findBy(array('id_gallery' => $id_gallery));
        return $creator;
    }

    public function getListUser($id_gallery)
    {
        $list = $this->em->getRepository(UserAccess::class)->findBy(array('id_gallery' => $id_gallery));
        $users = [];
        foreach ($list as $user) {
            array_push($users, $user->getIdUser());
        }
        return $users;
    }
}
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
use Psr\Log\LoggerInterface;
use App\Models\User;
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
            if($index !== null){
                $picture = $this->em->getRepository(Picture::class)->find(['id' => $index->getIdPicture()]);
                return $picture;
            }
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


   public function getGalleryInfo($id){
    try{
        $gallery = $this->em->getRepository(Gallery::class)->find($id);
        $creator = $this->em->getRepository(UserToGallery::class)->findOneBy(['id_gallery' => $id]);
        $user = $this->em->getRepository(User::class)->find($creator->getIdUser());
        $tags = $this->em->getRepository(GalleryToTag::class)->findBy(['id_gallery' => $id]);
        $tagsList = [];
        foreach($tags as $tag){
            $tagg = $this->em->getRepository(Tag::class)->find($tag->getIdTag());
            array_push($tagsList, $tagg->getTag());
        }
        $users = $this->em->getRepository(UserAccess::class)->findBy(['id_gallery' => $id]);
        $usersList = [];
        foreach($users as $user){
            $user = $this->em->getRepository(User::class)->find($user->getIdUser());
            array_push($usersList, $user->getUsername());
        }
        $galleryInfo = array(
            "id" => $gallery->getId(),
            "name" => $gallery->getName(),
            "description" => $gallery->getDescription(),
            "nb_pictures" => $gallery->getNbPictures(),
            "public" => $gallery->getPublic(),
            "creator" => $user->getUsername(),
            "tags" => $tagsList,
            "users" => $usersList
        );
        return $galleryInfo;
    }catch(\Exception $e){
        $this->logger->error("Erreur lors de la récupération des informations de la galerie $id : " . $e->getMessage());
        return null;
    }
   }
}
 
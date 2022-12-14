<?php

namespace App\Services;

use App\Models\Gallery;
use App\Models\Tag;
use App\Models\Picture;
use App\Models\GalleryToTag;
use App\Models\GalleryToPicture;
use App\Models\UserToGallery;
use App\Models\UserAccess;
use App\Models\PictureToComment;
use App\Models\MetaData;
use App\Models\PictureToMetadata;
use App\Models\Comment;
use App\Models\PictureToTag;
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

    public function getGalleryById(int $id): ?Gallery
    {
        try {
            $gallery = $this->em->getRepository(Gallery::class)->findOneBy(['id' => $id]);
            return $gallery;
        } catch (\Exception $e) {
            $this->logger->error("Error while getting user by username: " . $e->getMessage());
            return "error";
        }
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
            $this->logger->error("Erreur lors de la cr??ation de la galerie $name : " . $e->getMessage());
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
        $galleries = $this->em->getRepository(Gallery::class)->findBy(['public' => 1], ['id' => 'DESC'], 15, $offsetPublic);
        return $galleries;
    }

    public function listPrivateGalleries($idUser, $offsetPrivate = 0)
    {
        $userToGalleries = $this->em->getRepository(UserToGallery::class)->findBy(['id_user' => $idUser]);
        $userGalleries = [];
        foreach($userToGalleries as $userToGallery){
            $userGalleries[] = $this->em->getRepository(Gallery::class)->findOneBy(['id' => $userToGallery->getIdGallery()]);
        }

        $userAccess = $this->em->getRepository(UserAccess::class)->findBy(['id_user' => $idUser]);
        $userAccessGalleries = [];
        foreach($userAccess as $access){
            $userAccessGalleries[] = $this->em->getRepository(Gallery::class)->findOneBy(['id' => $access->getIdGallery()]);
        }

        $PrivateGalleries = array_merge($userGalleries, $userAccessGalleries);

        $PrivateGalleries = array_slice($PrivateGalleries, $offsetPrivate, 15);

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
            $this->logger->error("Erreur lors de la r??cup??ration de la galerie ou des photos $id : " . $e->getMessage());
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

    public function deleteGallery()
    {
        
            $id_gallery = $_POST['id_gallery'];
            $gallery = $this->em->getRepository(Gallery::class)->find($id_gallery);
            $user_access = $this->em->getRepository(UserAccess::class)->findBy(array('id_gallery' => $id_gallery));
            $user_to_gallery = $this->em->getRepository(UserToGallery::class)->findBy(array('id_gallery' => $id_gallery));
            $gallery_to_picture = $this->em->getRepository(GalleryToPicture::class)->findBy(array('id_gallery' => $id_gallery));
            $gallery_to_tag = $this->em->getRepository(GalleryToTag::class)->findBy(array('id_gallery' => $id_gallery));
            $pictures = [];
            foreach ($gallery_to_picture as $id_picture) {
                if (!empty($id_picture))
                    array_push($pictures, $this->em->getRepository(Picture::class)->find($id_picture->getIdPicture()));
            }
            $picture_to_metadata = [];
            $picture_to_comment = [];
            $picture_to_tag = [];
            foreach ($pictures as $picture) {
                if (!empty($picture))
                    array_push($picture_to_metadata, $this->em->getRepository(PictureToMetadata::class)->findBy(array('id_picture' => $picture->getId())));
                    array_push($picture_to_comment, $this->em->getRepository(PictureToComment::class)->findBy(array('id_picture' => $picture->getId())));
                    array_push($picture_to_tag, $this->em->getRepository(PictureToTag::class)->findBy(array('id_picture' => $picture->getId())));
            }
            $metadatas = [];
            foreach ($picture_to_metadata as $id_metadata) {
                if (!empty($id_metadata))
                    array_push($metadatas, $this->em->getRepository(MetaData::class)->find($id_metadata->getIdMetadata()));
            }
            $comments = [];
            foreach ($picture_to_comment as $id_comment) {
                if (!empty($id_metadata))
                    array_push($comment, $this->em->getRepository(Comment::class)->find($id_comment->getIdComment()));
            }


            // supprimer le lien entre les utilisateur qui y ont acc??s et la galerie
            foreach ($user_access as $a) {
                $this->em->remove($a);
            }
        
            // supprimer le lien entre le cr??ateur et la galerie
            $this->em->remove($user_to_gallery[0]);

            // supprimer les metadatas des images de la galerie
            foreach ($metadatas as $metadata) {
                $this->em->remove($metadata);
            }
            

            // supprimer le lien entre les images et leurs metadata
            foreach($picture_to_metadata as $a) {
                foreach($a as $b) {
                    $this->em->remove($b);
                }
            }
            
            // supprimer les commentaire d'une image 
            foreach($comments as $comment) {
                $this->em->remove($comment);
            }
            
            
            // supprimer le lien entre les commentaires et les images 
            foreach($picture_to_comment as $a) {
                foreach($a as $b) {
                    $this->em->remove($b);
                }
            }
            

            // supprimer le lien entre les images et leur tag
            foreach($picture_to_tag as $a) {
                foreach($a as $b) {
                    $this->em->remove($b);
                }
            }
            

            // supprimer le lien entre la gallerie et ses tag
            foreach($gallery_to_tag as $a) {
                $this->em->remove($a);
            }
            
            
            // supprimer les images de la galerie
            foreach($pictures as $picture) {
                $this->em->remove($picture);
            }
            

            // supprimer le lien entre une image et la galerie
            foreach($gallery_to_picture as $a) {
                $this->em->remove($a);
            }
            

            // supprimer la galerie
            $this->em->remove($gallery);
            $this->em->flush();
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
            "createdAt" => $gallery->getCreatedAt()->format('D/M/Y'),
            "tags" => $tagsList,
            "users" => $usersList
        );
        return $galleryInfo;
    }catch(\Exception $e){
        $this->logger->error("Erreur lors de la r??cup??ration des informations de la galerie $id : " . $e->getMessage());
        return null;
    }
   }


   public function editGallery($id, $name, $description, $public, $tags, $users){
    try{
        $gallery = $this->em->getRepository(Gallery::class)->find($id);
        $gallery->setName($name);
        $gallery->setDescription($description);
        $gallery->setPublic($public);
        $this->em->persist($gallery);
        
        $this->logger->info("Galerie $id modifi??e");
        // r??cup??re les tags de la galerie
        $tagsGallery = $this->em->getRepository(GalleryToTag::class)->findBy(['id_gallery' => $id]);

        // r??cup??re les objet Tag de tagsGallery
        $tagsGalleryList = [];
        foreach($tagsGallery as $tag){
            $tagg = $this->em->getRepository(Tag::class)->find($tag->getIdTag());
            array_push($tagsGalleryList, $tagg->getTag());
        }
        // supprime les tags de la galerie qui ne sont pas dans la liste des tags
        foreach($tagsGalleryList as $tag){
            if(!in_array($tag, $tags)){
                $tagToDelete = $this->em->getRepository(Tag::class)->findOneBy(['tag' => $tag]);
                $this->logger->info("Gallery " . $id . " : suppression du tag " . $tag);
                $linkToDelete = $this->em->getRepository(GalleryToTag::class)->findOneBy(['id_gallery' => $id, 'id_tag' => $tagToDelete->getId()]);
                if (!empty($linkToDelete)) {
                    $this->em->remove($linkToDelete);
                }
                $this->logger->info("Tag". $tag ."supprim??");
            }
        }
        // ajoute les tags qui ne sont pas dans la liste des tags de la galerie
        foreach($tags as $tag){
            if(!in_array($tag, $tagsGalleryList)){
                $tagExist = $this->em->getRepository(Tag::class)->findOneBy(['tag' => $tag]);
                if($tagExist === null){
                    $tag = new Tag($tag);
                    $this->em->persist($tag);
                    $this->em->flush();
                    $this->logger->info("Tag". $tag->getTag() ."ajout??");

                    $newTag = $this->em->getRepository(Tag::class)->findOneBy(['tag' => $tag->getTag()]);
                    $galleryToTag = new GalleryToTag($gallery->getId(), $newTag->getId());


                    $this->em->persist($galleryToTag);
                    $this->logger->info("Lien ". $tag->getTag() ."ajout??");

                }else{
                    $galleryToTag = new GalleryToTag($gallery->getId(), $tagExist->getId());
                    $this->em->persist($galleryToTag);
                    $this->logger->info("Lien ". $tagExist->getTag() ."ajout??");
                }
            }
        }


        // r??cup??re les utilisateurs de la galerie
        $usersGallery = $this->em->getRepository(UserAccess::class)->findBy(['id_gallery' => $id]);
        // supprime les utilisateurs de la galerie qui ne sont pas dans la liste des utilisateurs
        $userList = [];
        foreach($usersGallery as $user){
            $user = $this->em->getRepository(User::class)->find($user->getIdUser());
            array_push($userList, $user->getUsername());
        }
        foreach($userList as $user){
            if(!in_array($user, $users)){
                $userToDelete = $this->em->getRepository(User::class)->findOneBy(['username' => $user]);
                $linkToDelete = $this->em->getRepository(UserAccess::class)->findOneBy(['id_gallery' => $id, 'id_user' => $userToDelete->getId()]);
                $this->em->remove($linkToDelete);
                $this->logger->info("Utilisateur". $user ."supprim??");
            }
        }
        // ajoute les utilisateurs qui ne sont pas dans la liste des utilisateurs de la galerie
        foreach($users as $user){
            if(!in_array($user, $usersGallery)){
                $userToAdd = $this->em->getRepository(User::class)->findOneBy(['username' => $user]);
                if($userToAdd !== null){
                    $userAccess = new UserAccess($userToAdd->getId(),$gallery->getId());
                    $this->em->persist($userAccess);
                    $this->logger->info("Utilisateur ". $userToAdd->getUsername() ."ajout??");
                }
            }
        }
        $this->em->flush();
        return true;
    }catch(\Exception $e){
        $this->logger->error("Erreur lors de la modification de la galerie $id : " . $e->getMessage() . " at " . $e->getFile() . " line " . $e->getLine());
        return false;
    }
   }
}


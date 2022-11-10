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
use App\Models\Metadata;
use App\Models\PictureToMetadata;
use App\Models\Comment;
use App\Models\PictureToTag;
use Doctrine\ORM\EntityManager;
use GMP;
use Psr\Log\LoggerInterface;


final class GalleryService {

    private EntityManager $em;

    public function __construct(EntityManager $em, LoggerInterface $logger) {
        $this->em = $em;
        $this->logger = $logger;
    }

    public function create(string $name, string $descr, int $nb_pictures, int $public, int $idUser, array $tags){
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
            $this->logger->info("Link between gallery $name and user number" . $idUser . " has been created");
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
                    array_push($metadatas, $this->em->getRepository(Metadata::class)->find($id_metadata->getIdMetadata()));
            }
            $comments = [];
            foreach ($picture_to_comment as $id_comment) {
                if (!empty($id_metadata))
                    array_push($comment, $this->em->getRepository(Comment::class)->find($id_comment->getIdComment()));
            }


            // supprimer le lien entre les utilisateur qui y ont accès et la galerie
            foreach ($user_access as $a) {
                $this->em->remove($a);
            }
        
            // supprimer le lien entre le créateur et la galerie
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
}
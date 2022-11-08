<?php

namespace App\Models;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;

#[Entity, Table(name: 'GalleriesToPictures')]
final class GalleryToPicture
{
    #[Id, Column(type: 'integer', nullable: false)]
    private int $id_gallery;

    #[Id, Column(type: 'integer', nullable: false)]
    private int $id_picture;

    public function __construct(int $id_gallery, int $id_picture)
    {
        $this->id_gallery = $id_gallery;
        $this->id_picture = $id_picture;
    }

    public function getIdGallery(): int 
    {
        return $this->id_gallery;
    }

    public function getIdPicture(): int 
    {
        return $this->id_picture;
    }
}
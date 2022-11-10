<?php

namespace App\Models;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;

#[Entity, Table(name: 'PicturesToMetadatas')]
final class PictureToMetadata
{
    #[Id, Column(type: 'integer', nullable: false)]
    private int $id_picture;

    #[Id, Column(type: 'integer', nullable: false)]
    private int $id_metadata;

    public function __construct(int $id_picture, int $id_metadata)
    {
        $this->id_picture = $id_picture;
        $this->id_metadata = $id_metadata;
    }

    public function getIdPicture(): int 
    {
        return $this->id_picture;
    }

    public function getIdMetadata(): int 
    {
        return $this->id_metadata;
    }
}
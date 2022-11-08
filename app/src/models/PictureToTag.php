<?php

namespace App\Models;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;

#[Entity, Table(name: 'PicturesToTags')]
final class PictureToTag
{
    #[Id, Column(type: 'integer', nullable: false)]
    private int $id_picture;

    #[Id, Column(type: 'integer', nullable: false)]
    private int $id_tag;

    public function __construct(int $id_picture, int $id_tag)
    {
        $this->id_picture = $id_picture;
        $this->id_tag = $id_tag;
    }

    public function getIdPicture(): int 
    {
        return $this->id_picture;
    }

    public function getIdTag(): int {
        return $this->id_tag;
    }
}
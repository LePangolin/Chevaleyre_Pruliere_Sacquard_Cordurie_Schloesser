<?php

namespace App\Models;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;

#[Entity, Table(name: 'PicturesToComments')]
final class PictureToComment
{
    #[Id, Column(type: 'integer', nullable: false)]
    private int $id_picture;

    #[Id, Column(type: 'integer', nullable: false)]
    private int $id_comment;

    public function __construct(int $id_picture, int $id_comment)
    {
        $this->id_picture = $id_picture;
        $this->id_comment = $id_comment;
    }

    public function getIdPicture(): int 
    {
        return $this->id_picture;
    }

    public function getIdComment(): int 
    {
        return $this->id_comment;
    }
}
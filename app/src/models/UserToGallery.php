<?php

namespace App\Models;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;

#[Entity, Table(name: 'UsersToGalleries')]
final class UserToGallery
{
    #[Id, Column(type: 'integer', nullable: false)]
    private int $id_user;

    #[Id, Column(type: 'integer', nullable: false)]
    private int $id_gallery;

    public function __construct(int $id_user, int $id_gallery)
    {
        $this->id_user = $id_user;
        $this->id_gallery = $id_gallery;
    }

    public function getIdUser(): int
    {
        return $this->id_user;
    }

    public function getIdGallery(): int 
    {
        return $this->id_gallery;
    }
}
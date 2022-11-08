<?php

namespace App\Models;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;

#[Entity, Table(name: 'Pictures')]
final class Picture
{
    #[Id, Column(type: 'integer'), GeneratedValue(strategy: 'AUTO')]
    private int $id;

    #[Column(type: 'string', nullable: true)]
    private string $name;

    #[Column(type: 'string', nullable: true)]
    private string $descr;

    #[Column(type: 'string', nullable: true)]
    private string $link;

    public function __construct() {

    }

    public function getId(): int 
    {
        return $this->id;
    }

    public function getName(): string 
    {
        return $this->name;
    }

    public function getDescr() : string
    {
        return $this->descr;
    }

    public function getLink() : string
    {
        return $this->link;
    }
}
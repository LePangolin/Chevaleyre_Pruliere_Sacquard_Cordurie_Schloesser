<?php

namespace App\Models;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;

#[Entity, Table(name: 'Tags')]
final class Tag
{
    #[Id, Column(type: 'integer'), GeneratedValue(strategy: 'AUTO')]
    private int $id;

    #[Column(type: 'string', nullable: false)]
    private string $tag;

    public function __construct(string $tag)
    {
        $this->tag = $tag;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTag(): string 
    {
        return $this->tag;
    }
}
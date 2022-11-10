<?php

namespace App\Models;

use DateTimeImmutable;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;

#[Entity, Table(name: 'Galleries')]
final class Gallery
{
    #[Id, Column(type: 'integer'), GeneratedValue(strategy: 'AUTO')]
    private int $id;

    #[Column(type: 'string', nullable: false)]
    private string $name;

    #[Column(type: 'string', nullable: true)]
    private string $description;

    #[Column(type: 'integer', nullable: false)]
    private int $nb_pictures;

    #[Column(type: 'boolean', nullable: false)]
    private bool $public;

    #[Column(name: 'registered_at', type: 'datetimetz_immutable', nullable: false)]
    private DateTimeImmutable $created_at; 

    public function __construct(string $name, string $description, int $nb_pictures, bool $public)
    {
        $this->name = $name;
        $this->description = $description;
        $this->nb_pictures = $nb_pictures;
        $this->public = $public;
        $this->created_at = new DateTimeImmutable();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getNbPictures(): int
    {
        return $this->nb_pictures;
    }

    public function getPublic(): bool
    {
        return $this->public;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->created_at;
    }

    public function getDescription(): string
    {
        return $this->description;
    }
}
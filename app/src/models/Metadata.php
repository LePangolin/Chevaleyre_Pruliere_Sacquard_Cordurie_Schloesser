<?php

namespace App\Models;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;

#[Entity, Table(name: 'Metadatas')]
final class Metadata
{
    #[Id, Column(type: 'integer'), GeneratedValue(strategy: 'AUTO')]
    private int $id;

    #[Column(type: 'json', nullable: true)]
    private array $value;

    public function __construct(string $value)
    {
        $this->value = $value;
    }

    public function getId(): int 
    {
        return $this->id;
    }

    public function getValue(): array 
    {
        return $this->value;
    }
}
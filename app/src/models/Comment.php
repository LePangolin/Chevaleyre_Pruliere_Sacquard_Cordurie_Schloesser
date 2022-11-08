<?php

namespace App\Models;

use DateTimeImmutable;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;

#[Entity, Table(name: 'Comments')]
final class Comment
{
    #[Id, Column(type: 'integer'), GeneratedValue(strategy: 'AUTO')]
    private int $id;

    #[Column(type: 'integer', nullable: false)]
    private int $id_author;

    #[Column(type: 'string', nullable: false)]
    private string $content;

    #[Column(name: 'registered_at', type: 'datetimetz_immutable', nullable: false), GeneratedValue(strategy: 'CURRENT_TIMESTAMP')]
    private DateTimeImmutable $sent_at;

    public function __construct(int $id_author, string $content) {
        $this->id_author = $id_author;
        $this->content = $content;
        $this->sent_at = new DateTimeImmutable();
    }

    public function getId(): int 
    {
        return $this->id;
    }

    public function getIdAuthor(): int 
    {
        return $this->id_author;
    }

    public function getContent(): string 
    {
        return $this->content;
    }

    public function getSentAt(): DateTimeImmutable
    {
        return $this->sent_at;
    }
} 
<?php

namespace App\Models;

use DateTimeImmutable;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;

#[Entity, Table(name: 'Users')]
final class User
{
    #[Id, Column(type: 'integer'), GeneratedValue(strategy: 'AUTO')]
    private int $id;

    #[Column(type: 'string', unique: true, nullable: false)]
    private string $username;

    #[Column(type: 'string', nullable: true)]
    private string $biography;

    #[Column(type: 'string', nullable: true)]
    private string $profile_picture;

    #[Column(type: 'string', nullable: false)]
    private string $password;

    #[Column(name: 'registered_at', type: 'datetimetz_immutable', nullable: false), GeneratedValue(strategy: 'CURRENT_TIMESTAMP')]
    private DateTimeImmutable $created_at;

    public function __construct(string $username, string $password)
    {
        $this->username = filter_var($username);
        $this->password = hash('md5', filter_var($password));
        $this->created_at = new DateTimeImmutable();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUsername(): string 
    {
        return $this->username;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->created_at;
    }
}
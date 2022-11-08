<?php 

namespace App\Services;

use App\Models\User;
use Doctrine\ORM\EntityManager;
use Psr\Log\LoggerInterface;

class UserService{

    public function __construct(EntityManager $em, LoggerInterface $logger){
        $this->em = $em;
        $this->logger = $logger;
    }

    public function getUserByUsername(string $username): ?User
    {
        return $this->em->getRepository(User::class)->findOneBy(['username' => $username]);
    }

    public function signUp(string $username, string $password): bool
    {
        $user = $this->getUserByUsername($username);
        if($user !== null){
            $this->logger->info("User $username already exists");
            return false;
        }
        $user = new User($username,  $password);
        $this->em->persist($user);
        $this->em->flush();
        $this->logger->info("User $username has been created");
        return true;
    }


    public function login(string $username, string $password) : ?User 
    {
        $user = $this->em->getRepository(User::class)->findOneBy(['username' => $username, 'password' => hash('md5', $password)]);
        if($user === null){
            $this->logger->info("User $username has failed to login");
            return null;
        }
        $this->logger->info("User $username has logged in");
        return $user;
    }
}



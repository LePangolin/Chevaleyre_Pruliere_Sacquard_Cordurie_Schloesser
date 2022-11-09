<?php 

namespace App\Services;

use App\Models\User;
use Doctrine\ORM\EntityManager;
use Psr\Log\LoggerInterface;

use App\Models\UserToGallery;
use App\Models\Gallery;

class UserService{

    public function __construct(EntityManager $em, LoggerInterface $logger){
        $this->em = $em;
        $this->logger = $logger;
    }

    public function getUserByUsername(string $username): ?User
    {
        try {
            $user = $this->em->getRepository(User::class)->findOneBy(['username' => $username]);
            return $user;
        } catch (\Exception $e) {
            $this->logger->error("Error while getting user by username: " . $e->getMessage());
            return "error";
        }
    }

    public function signUp(string $username, string $password): bool
    {
        $user = $this->getUserByUsername($username);
        if($user === "error"){
            $this->logger->error("Error while getting user by username");
            return false;
        }
        if($user !== null){
            $this->logger->info("User $username already exists");
            return false;
        }
        try {
            $user = new User($username, $password);
            $this->em->persist($user);
            $this->em->flush();
            $this->logger->info("User $username created");
            return true;
        } catch (\Exception $e) {
            $this->logger->error("Error while creating the User  : ".$e->getMessage());
            return false;
        }
    }


    public function login(string $username, string $password) : ?User 
    {
        try{
            $user = $this->em->getRepository(User::class)->findOneBy(['username' => $username, 'password' => hash('md5', $password)]);
            if($user === null){
                $this->logger->info("User $username has failed to login");
                return null;
            }
            $this->logger->info("User $username has logged in");
            return $user;    
        }catch(\Exception $e){
            $this->logger->error("Error while getting user by username and password: " . $e->getMessage());
            return null;
        }
    }

    public function getUserInfo(int $id){
        try{
            $userToGalleries = $this->em->getRepository(UserToGallery::class)->findBy(['id_user' => $id]);
            $usergalleries = [];
            foreach($userToGalleries as $userToGallery){
                $usergalleries[] = $this->em->getRepository(Gallery::class)->findOneBy(['id' => $userToGallery->getIdGallery()]);
            }
           

            $tabFinal = [];
            $tabFinal['MyGalleries'] =  $usergalleries;

            $this->logger->info("User $id has got his galleries");
            return $tabFinal;
        }catch(\Exception $e){
            $this->logger->error("Error while getting user galleries : " . $e->getMessage());
            $tabFinal = [];
            $tabFinal['MyGalleries'] =  [];
            return $tabFinal;
        }
    }
}
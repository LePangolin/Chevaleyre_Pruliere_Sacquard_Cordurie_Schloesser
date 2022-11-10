<?php

namespace App\Services;

use App\Models\Gallery;
use App\Models\Tag;
use App\Models\Picture;
use App\Models\PictureToTag;
use App\Models\GalleryToPicture;
use Doctrine\ORM\EntityManager;
use GMP;
use Psr\Log\LoggerInterface;


final class ImageService
{

    private EntityManager $em;

    public function __construct(EntityManager $em, LoggerInterface $logger, GalleryService $gs)
    {
        $this->em = $em;
        $this->logger = $logger;
        $this->gs = $gs;
    }

}

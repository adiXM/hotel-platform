<?php

namespace App\Service;

use App\Entity\Media;
use App\Entity\RoomType;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;

class MediaService implements MediaServiceInterface
{
    private ObjectManager $entityManager;

    public function __construct(
        private SluggerInterface $slugger,
        private readonly ManagerRegistry $doctrine)
    {
        $this->entityManager = $this->doctrine->getManager();
    }


    /**
     * @throws FileException
     */
    public function uploadMedia(UploadedFile $file, string $directory): string
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        // this is needed to safely include the file name as part of the URL
        $safeFilename = $this->slugger->slug($originalFilename);
        $newFilename = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

        // Move the file to the directory where brochures are stored
        $file->move($directory, $newFilename);

        return $newFilename;
    }
}
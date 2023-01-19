<?php

namespace App\Service;

use App\Entity\RoomType;
use Symfony\Component\HttpFoundation\File\UploadedFile;

interface MediaServiceInterface
{
    public function uploadMedia(UploadedFile $file, string $directory): string;

}
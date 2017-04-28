<?php
// src/AppBundle/FileUploader.php
namespace Application\PlateformeBundle\Services;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader
{
    public function upload(UploadedFile $file, $targetDir)
    {
        $fileName = sha1(uniqid()).'.'.$file->guessExtension();
  
        $file->move(__DIR__.'/../../../../web/'.$targetDir, $fileName);

        return $fileName;
    }
}


?>
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

    public function uploadAvatar(UploadedFile $file, $targetDir, $lastPicture){

        $dir = __DIR__.'/../../../../web/'.$targetDir;

        if (file_exists($dir.'/'.$lastPicture))
            unlink($dir.'/'.$lastPicture);

        $fileName = 'profile'.'.'.$file->guessExtension();
        $file->move($dir, $fileName);

        return $fileName;
    }
}


?>
<?php

namespace Application\PlateformeBundle\Services;

use Application\PlateformeBundle\Entity\Beneficiaire;

class Document
{
    /**
     * permet de décompresser l'image qu'on affiche puis le supprimer à la fin de l'affichage
     *
     * @param Beneficiaire $beneficiaire the Entity
     * @param String $nomFichier
     */
    public function afficherDocument(Beneficiaire $beneficiaire, $nomFichier){


        $zip = new \ZipArchive();
        $zip_path=$this->getUploadRootDir($beneficiaire).'/download.zip';

        if($zip->open($zip_path) === TRUE){
            $zip->extractTo($this->getUploadRootDir($beneficiaire),$nomFichier);
            $temp = tmpfile();

            fwrite($temp,file_get_contents($this->getUploadRootDir($beneficiaire).'/'.$nomFichier));

            rewind($temp);

            $file_content = stream_get_contents($temp);

            echo $file_content;

            echo sprintf('<img src="data:image/png;base64,%s" />', base64_encode($zip->getFromName('Fichier.extension')));

            $zip->close();




        }
        else{
            echo 'echec';
        }
    }

    /**
     * @param \Application\PlateformeBundle\Entity\Document $document
     *
     */
    public function supprimerDocument(Beneficiaire $beneficiaire ,\Application\PlateformeBundle\Entity\Document $document){
        sleep(20);
        $tempFile = $this->getAbsolutePath($beneficiaire,$document);
        if (file_exists($tempFile))
            unlink($tempFile);
    }



    public function getWebDir(Beneficiaire $beneficiaire)
    {
        return 'uploads/beneficiaire/documents/'.$beneficiaire->getId();
    }

    protected function getUploadDir(Beneficiaire $beneficiaire)
    {
        return __DIR__.'/../Resources/public/uploads/Documents/.'.$beneficiaire->getId();
    }

    protected function getUploadRootDir($beneficiaire)
    {
        return __DIR__.'/../../../../web/'.$this->getWebDir($beneficiaire);
    }

    public function getAbsolutePath(Beneficiaire $beneficiaire ,$document)
    {
        return null === $document->getPath() ? null : $this->getUploadRootDir($beneficiaire).'/'.$document->getPath();
    }
}
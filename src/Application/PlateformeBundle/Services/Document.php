<?php

namespace Application\PlateformeBundle\Services;

use Application\PlateformeBundle\Entity\Beneficiaire;
use Doctrine\ORM\EntityManager;
use Knp\Bundle\SnappyBundle\Snappy\LoggableGenerator;


class Document
{
    protected $em;
    protected $knp;

    public function __construct(EntityManager $em, LoggableGenerator $knp)
    {
        $this->em = $em;
        $this->knp = $knp;
    }

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
            $zip->close();
        }
        else{
            echo 'echec';
        }
    }

    /**
     * cette fonction permet de supprimer les fichiers qui sont hors du zip, utilisé par la cron pour vider les documents
     * et si bool est vrai, elle supprime également le fichier dans le zip et le supprime dans le document aussi
     *
     * @param \Application\PlateformeBundle\Entity\Document $document
     *
     */
    public function removeDocument(Beneficiaire $beneficiaire ,\Application\PlateformeBundle\Entity\Document $document, $bool = false){
        $tempFile = $this->getAbsolutePath($beneficiaire,$document);
        if (file_exists($tempFile))
            unlink($tempFile);

        if ($bool == true){
            $zip = new \ZipArchive();
            $zip_path = $this->getUploadRootDir($beneficiaire).'/download.zip';
            if($zip->open($zip_path) === TRUE){
                $zip->deleteName($document->getPath());
                $this->em->remove($document);
                $this->em->flush();
            }else {
                die ("An error occurred opening your ZIP file.");
            }
            $zip->close();
        }
    }

    public function saveDocument(Beneficiaire $beneficiaire, $type, $html){

        $this->knp->getInternalGenerator()->setTimeout(300);

        // Suppression accent dans le nom du beneficiaire pour pas creer le bug du nom de fichier qui ne fonctionne pas avec un accent
        $nomBeneficiaire = $beneficiaire->getNomConso();
        $nomBeneficiaire = strtr($nomBeneficiaire, 'ÁÀÂÄÃÅÇÉÈÊËÍÏÎÌÑÓÒÔÖÕÚÙÛÜÝ', 'AAAAAACEEEEEIIIINOOOOOUUUUY');
        $nomBeneficiaire = strtr($nomBeneficiaire, 'áàâäãåçéèêëíìîïñóòôöõúùûüýÿ', 'aaaaaaceeeeiiiinooooouuuuyy');

        $file = "".ucfirst($type)."_VAE_".str_replace(" ","_",str_replace(".","",$beneficiaire->getCiviliteConso())."_".$nomBeneficiaire)."_".(new \DateTime('now'))->format('d')."_".(new \DateTime('now'))->format('m')."_".(new \DateTime('now'))->format('Y');

        $documents = $beneficiaire->getDocuments();

        if (!($documents->isEmpty())){
            $i = 0;
            $fileTmp = $file;
            foreach ($documents as $documentFile){
                for ( $j = 0; $j < count($documents); $j++){
                    if($documentFile->getPath() == $fileTmp.'.pdf') {
                        $i++;
                        $fileTmp = $file.'_('.$i.')';
                        break;
                    }
                }
            }
            if ($i != 0){
                $file = $fileTmp;
            }
        }
        $file .= '.pdf';
        $document = new \Application\PlateformeBundle\Entity\Document();
        $document->setBeneficiaire($beneficiaire);
        $document->setPath($file);
        $document->setDescription($file);
        $document->setType($type);

        $filename =  __DIR__."/../../../../web/uploads/beneficiaire/documents/".$beneficiaire->getId()."/".$file;
        $filepath =  __DIR__."/../../../../web/uploads/beneficiaire/documents/".$beneficiaire->getId();

        if (file_exists($filename)){
            unlink($filename);
        }

        $this->knp->generateFromHtml($html,$filename);

        $zip = new \ZipArchive();
        $zip_path=$filepath.'/download.zip';

        if($zip->open($zip_path) === TRUE){
        }else {
            if ($zip->open($zip_path, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) !== TRUE) {
                die ("An error occurred creating your ZIP file.");
            }
        }

        $zip->addFile($filename, $file);

        unset($filename);
        unset($this->file);

        $this->em->persist($document);
        $this->em->flush();
        
        $zip->close();
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
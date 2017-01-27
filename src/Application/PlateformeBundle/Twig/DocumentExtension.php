<?php

namespace Application\PlateformeBundle\Twig;

use Application\PlateformeBundle\Entity\Beneficiaire;
use Application\PlateformeBundle\Services\Document;

class DocumentExtension extends \Twig_Extension
{
    /**
     * var Document
     */
    private $document;

    public function __construct(Document $document)
    {
        $this->document = $document;
    }

    public function afficherTwigDocument(Beneficiaire $beneficiaire, $nomFichier){
        return $this->document->afficherDocument($beneficiaire, $nomFichier);
    }

    public function supprimerTwigDocument(Beneficiaire $beneficiaire,$document){
        return $this->document->supprimerDocument($beneficiaire, $document);
    }

    public function getFunctions(){
        return array(
            new \Twig_SimpleFunction('afficherDocument', array($this, 'afficherTwigDocument')),
            new \Twig_SimpleFunction('supprimerDocument', array($this, 'supprimerTwigDocument')),
        );
    }

    public function getName(){
        return 'aplication_document';
    }
}
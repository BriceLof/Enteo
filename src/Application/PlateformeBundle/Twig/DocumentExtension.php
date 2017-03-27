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

    public function dateFr($date){
        $Jour = array("Dimanche", "Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi","Samedi");
        $Mois = array("Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre");
        $datefr = $Jour[date_format($date,"w")]." ".date_format($date, "d")." ".$Mois[date_format($date,"n")]." ".date_format($date, "Y");
        echo $datefr;
    }

    public function dateCourtFr($date){
        $Mois = array("Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre");
        $datefr = date_format($date, "d")." ".$Mois[date_format($date,"n")]." ".date_format($date, "Y");
        echo $datefr;
    }

    public function getFunctions(){
        return array(
            new \Twig_SimpleFunction('afficherDocument', array($this, 'afficherTwigDocument')),
            new \Twig_SimpleFunction('supprimerDocument', array($this, 'supprimerTwigDocument')),
            new \Twig_SimpleFunction('dateFr', array($this, 'dateFr')),
            new \Twig_SimpleFunction('dateCourtFr', array($this, 'dateCourtFr')),
        );
    }

    public function getName(){
        return 'application_document';
    }
}
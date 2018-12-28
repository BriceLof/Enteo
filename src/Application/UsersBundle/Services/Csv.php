<?php

namespace Application\UsersBundle\Services;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Doctrine\ORM\EntityManager;
use Doctrine\Common\Collections;

class Csv
{
    protected $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function getCsvExpirationVigilance($consultants){
        $handle = fopen('php://temp', 'r+');
        fputcsv($handle, array(
            'nom',
            'prenom',
            'Fin attestation vigilance ursaff',
        ), ';');

        foreach ($consultants as $consultant) {

            if (is_null($consultant->getFacturation())){
                $date = "n'a pas encore d'attestation";
            }else{
                if (is_null($consultant->getFacturation()->getDate())){
                    $date = "n'a pas encore d'attestation";
                }else{
                    $date = $consultant->getFacturation()->getDate()->format("d/m/Y");
                }
            }

            fputcsv(
                $handle,
                array(
                    utf8_decode(strtoupper($consultant->getNom())),
                    utf8_decode(ucfirst($consultant->getPrenom())),
                    $date,
                ),
                ';'
            );
        }
        rewind($handle);
        $csv = stream_get_contents($handle);
        fclose($handle);
        return $csv;
    }

    public function getCsvNumDeclarationActivite($consultants){
        $handle = fopen('php://temp', 'r+');
        fputcsv($handle, array(
            'nom',
            'prenom',
        ), ';');

        foreach ($consultants as $consultant) {

            fputcsv(
                $handle,
                array(
                    utf8_decode(strtoupper($consultant->getNom())),
                    utf8_decode(ucfirst($consultant->getPrenom())),
                ),
                ';'
            );
        }
        rewind($handle);
        $csv = stream_get_contents($handle);
        fclose($handle);
        return $csv;
    }
}
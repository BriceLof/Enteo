<?php

namespace Application\PlateformeBundle\Services;

use Symfony\Component\HttpFoundation\StreamedResponse;

class Csv
{
    public function getCsvFromListBeneficiaire($beneficiaires){

        $response = new StreamedResponse();
        $response->setCallback(function() use ($beneficiaires) {
            $handle = fopen('php://output', 'w+');
            fputcsv($handle, array('id', 'Nom', 'Prenom', utf8_decode('Téléphone'), 'dateConfMer' ), ';');

            foreach ($beneficiaires as $beneficiaire) {
                fputcsv(
                    $handle,
                    array(
                        $beneficiaire->getId(),
                        utf8_decode($beneficiaire->getNomConso()),
                        utf8_decode($beneficiaire->getPrenomConso()),
                        $beneficiaire->getTelConso(),
                        $beneficiaire->getDateConfMer()->format('d-m-Y')),
                    ';'
                );
            }
            fclose($handle);
        });

        $response->setStatusCode(200);
        $response->headers->set('Content-Type', 'text/csv; charset=utf-8');
        $response->headers->set('Content-Disposition', 'attachment; filename="export.csv"');
        return $response;
    }
}
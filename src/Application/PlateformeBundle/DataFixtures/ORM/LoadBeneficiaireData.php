<?php


namespace AppBundle\DataFixtures\ORM;

use Application\PlateformeBundle\Entity\Historique;
use Application\PlateformeBundle\Entity\News;
use Application\PlateformeBundle\Entity\Ville;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Application\PlateformeBundle\Entity\Beneficiaire;

class LoadBeneficiaireData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {

        $ville = new Ville();
        $ville->setNom("Antony");
        $ville->setZip("92160");
        $manager->persist($ville);
        $manager->flush();
        for($i = 0; $i < 10 ; $i++)
        {
            $beneficiaire = new Beneficiaire();
            $beneficiaire->setEncadrement("oui");
            $beneficiaire->setPoste("assistant");
            $beneficiaire->setTel2("0146650707");
            $beneficiaire->setEmail2("test@test.fr");
            $beneficiaire->setAdresse("2 rue flandre");
            $beneficiaire->setAdresseComplement("appt 12");
            $beneficiaire->setPays(1);
            $beneficiaire->setNumSecu("12311451221451");
            $beneficiaire->setDateNaissance(new \DateTime("1990-10-05"));
            $beneficiaire->setStatut("cadre");
            $beneficiaire->setHeureDif(10);
            $beneficiaire->setHeureCpf(10);
            $beneficiaire->setHeureCpfAnnee(50);
            $beneficiaire->setMotivation("promotion");
            $beneficiaire->setVille($ville);
            $beneficiaire->setClientId(1);
            $beneficiaire->setCiviliteConso("monsieur");
            $beneficiaire->setNomConso("Dujardin");
            $beneficiaire->setPrenomConso("jean");
            $beneficiaire->setVilleConso("paris");
            $beneficiaire->setHeureRappel("15h");
            $beneficiaire->setDateHeureMer(new \DateTime("2017-01-15 15:00:00"));
            $beneficiaire->setDateConfMer(new \DateTime("2017-01-19 10:30:27"));
            $beneficiaire->setTelConso("0645784125");
            $beneficiaire->setEmailConso("loulou@test.fr");
            $beneficiaire->setDomaineVae("maintenance");
            $beneficiaire->setDiplomeVise("master");
            $beneficiaire->setOrigineMer("iciformation");
            $beneficiaire->setNbAppelTel(2);

            $manager->persist($beneficiaire);
            $manager->flush();
        
            $news = new News();
            $news->setBeneficiaire($beneficiaire);
            $news->setStatut("confirmé");
            $news->setDetailStatut("test reussi");
            $news->setMessage("il faut que le bénéficiaire travaille plus son anglais pour qu'il puisse reussir la prochaine étape");

            $manager->persist($news);
            $manager->flush();

            $historique = new Historique();
            $historique->setBeneficiaire($beneficiaire);
            $historique->setDate(new \DateTime(2016-12-04));
            $historique->setEvenement("première étape");

            $manager->persist($historique);
            $manager->flush();
        }

    }
}


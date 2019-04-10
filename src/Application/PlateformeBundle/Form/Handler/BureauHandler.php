<?php

namespace Application\PlateformeBundle\Form\Handler;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use Application\PlateformeBundle\Entity\Bureau;

class BureauHandler
{

    private $imagesDir;

    /**
     * BureauHandler constructor.
     * @param $imagesDir
     */
    public function __construct($imagesDir)
    {
        $this->imagesDir = $imagesDir;
    }


    public function process(Bureau $bureau, Form $form, Request $request, EntityManagerInterface $em)
    {
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($bureau->getEnabledEntheor()) {
                $bureau = $this->saveImages($bureau, $form);
            }

            $ville = $em->getRepository('ApplicationPlateformeBundle:Ville')->find($form['ville_select']->getData());
            $bureau->setVille($ville);

            $this->onSuccess($bureau, $em);
            return true;
        }
        return false;
    }

    public function onSuccess($bureau, $em)
    {
        $em->persist($bureau);
        $em->flush();
    }

    public function saveImages(Bureau $bureau, Form $form)
    {
        if (!is_null($form['banner_image']->getData())) $bureau->setBanner($this->moveFile($form['banner_image']->getData()));
        if (!is_null($form['first_image']->getData())) $bureau->setFirstImage($this->moveFile($form['first_image']->getData()));
        if (!is_null($form['second_image']->getData())) $bureau->setSecondImage($this->moveFile($form['second_image']->getData()));
        if (!is_null($form['third_image']->getData())) $bureau->setThirdImage($this->moveFile($form['third_image']->getData()));

        return $bureau;
    }

    public function moveFile($file)
    {
        $fileName = md5(uniqid()) . '.' . $file->guessExtension();
        $file->move(
            $this->imagesDir,
            $fileName
        );
        return $fileName;
    }
}
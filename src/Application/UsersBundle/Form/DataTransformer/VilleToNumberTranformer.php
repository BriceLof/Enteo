<?php

namespace Application\UsersBundle\Form\DataTransformer;

use Application\PlateformeBundle\Entity\Ville;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class VilleToNumberTransformer implements DataTransformerInterface
{
    private $manager;

    public function __construct(ObjectManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * Transforms an object (ville) to a string (number).
     *
     * @param  Ville|null $ville
     * @return string
     */
    public function transform($ville)
    {
        if (null === $ville) {
            return '';
        }

        return $ville->getId();
    }

    /**
     * Transforms a string (number) to an object (ville).
     *
     * @param  string $villeNumber
     * @return Ville|null
     * @throws TransformationFailedException if object (ville) is not found.
     */
    public function reverseTransform($villeNumber)
    {
        // no ville number? It's optional, so that's ok
        if (!$villeNumber) {
            return;
        }

        $ville = $this->manager
            ->getRepository('ApplicationPlateformeBundle:Ville')
            // query for the ville with this id
            ->find($villeNumber)
        ;

        if (null === $ville) {
            // causes a validation error
            // this message is not shown to the user
            // see the invalid_message option
            throw new TransformationFailedException(sprintf(
                'An ville with number "%s" does not exist!',
                $villeNumber
            ));
        }

        return $ville;
    }
}
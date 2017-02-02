<?php

namespace AppBundle\Library;

use Doctrine\Bundle\MongoDBBundle\ManagerRegistry;
use JMS\Serializer\Annotation;

/**
 * Used for injection the doctrine service to ony class.
 * @Annotation\ExclusionPolicy("none")
 */
trait DoctrineTrait
{
    /**
     * @var ManagerRegistry
     * @Annotation\Exclude
     */
    protected $doctrine;

    /**
     * @param ManagerRegistry $doctrine
     *
     * @return $this
     */
    public function setDoctrine(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;

        return $this;
    }

    /**
     * @return ManagerRegistry
     */
    public function getDoctrine()
    {
        return $this->doctrine;
    }
}

<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Shipment;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;

/**
 * ShipmentRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ShipmentRepository extends \Doctrine\ORM\EntityRepository
{
    public function __construct(EntityManagerInterface $em,
                                ClassMetadata $metadata = null)
    {
        parent::__construct($em,
            $metadata == null ?
                new ClassMetadata(Shipment::class) : $metadata);
    }

    /**
     * @param Shipment $shipment
     * @return bool
     * @throws ORMException
     */
    public function insert(Shipment $shipment)
    {
        try {
            $this->_em->persist($shipment);
            $this->_em->flush();
            return true;
        } catch (OptimisticLockException $e) {
            return false;
        }
    }

    /**
     * @param Shipment $shipment
     * @return bool
     * @throws ORMException
     */
    public function update(Shipment $shipment)
    {
        try {
            $this->_em->merge($shipment);
            $this->_em->flush();
            return true;
        } catch (OptimisticLockException $e) {
            return false;
        }
    }

    /**
     * @param Shipment $shipment
     * @return bool
     * @throws ORMException
     */
    public function remove(Shipment $shipment)
    {
        try {
            $this->_em->remove($shipment);
            $this->_em->flush();
            return true;
        } catch (OptimisticLockException $e) {
            return false;
        }
    }
}

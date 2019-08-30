<?php


namespace AppBundle\Service\Shipments;


use AppBundle\Entity\Shipment;
use AppBundle\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;

interface ShipmentServiceInterface
{
    public function create(Shipment $shipment): bool;
    public function edit(Shipment $shipment): bool;
    public function delete(Shipment $shipment): bool;

    /**
     * @return ArrayCollection|Shipment[]
     */
    public function getAll();

    /**
     * @return ArrayCollection|Shipment[]
     */
    public function getAllNotShipped();

    /**
     * @param User $user
     * @return ArrayCollection|Shipment[]
     */
    public function getAllByUser(User $user);
}
<?php


namespace AppBundle\Service\ShipmentItems;


use AppBundle\Entity\Jewellery;
use AppBundle\Entity\Shipment;
use AppBundle\Entity\ShipmentItems;
use Doctrine\Common\Collections\ArrayCollection;

interface ShipmentItemsServiceInterface
{
    public function create(ShipmentItems $item): bool;
    public function edit(ShipmentItems $item): bool;
    public function delete(ShipmentItems $item): bool;

    /**
     * @param int $id
     * @return ArrayCollection|ShipmentItems[]
     */
    public function getAllByShipmentId(int $id);

    /**
     * @param Shipment $shipment
     * @param Jewellery $item
     * @return ArrayCollection|ShipmentItems[]
     */
    public function getOneByShipmentIdAndItemId(Shipment $shipment, Jewellery $item);

    /**
     * @param int $id
     * @return ArrayCollection|ShipmentItems[]
     */
    public function getAllByShipment(int $id);
}
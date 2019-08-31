<?php


namespace AppBundle\Controller;


use AppBundle\Entity\Jewellery;
use AppBundle\Entity\Shipment;
use AppBundle\Entity\ShipmentItems;
use AppBundle\Service\ShipmentItems\ShipmentItemsServiceInterface;
use Doctrine\Common\Collections\ArrayCollection;

class ShipmentItemsController
{
    /**
     * @var ShipmentItemsServiceInterface
     */
    private $shipmentItemsService;

    public function __construct(ShipmentItemsServiceInterface $shipmentItemsService)
    {
        $this->shipmentItemsService = $shipmentItemsService;
    }

    public function add(Shipment $shipment, Jewellery $item, int $quantity)
    {
        /** @var ShipmentItems $existingShipmentItem */
        $existingShipmentItem = $this->shipmentItemsService->getOneByShipmentIdAndItemId($shipment, $item);

        if ($existingShipmentItem === null) {
            $shipmentItem = $this->generateNewShipmentItem($shipment, $item, $quantity);
            $this->shipmentItemsService->create($shipmentItem);
        }
    }

    public function getItemsByShipment(int $id)
    {
        return $this->shipmentItemsService->getAllByShipment($id);
    }

    /**
     * @param $shipments
     * @return array
     */
    public function loadShipmentItems($shipments)
    {
        $shipmentItems = [];

        /** @var Shipment $shipment */
        foreach ($shipments as $shipment) {
            $items = $this->getItemsByShipment($shipment->getId());

            /** @var ShipmentItems $item */
            foreach ($items as $item) {
                $shipmentItem = $this->generateNewShipmentItem(
                    $item->getShipmentId(), $item->getItemId(), $item->getQuantity());
                $shipmentItems[] = $shipmentItem;
            }
            $shipment->setItems($shipmentItems);
            $shipmentItems = [];
        }
        return $shipmentItems;
    }

    /**
     * @param Shipment $shipment
     * @param Jewellery $item
     * @param int $quantity
     * @return ShipmentItems
     */
    private function generateNewShipmentItem(Shipment $shipment, Jewellery $item, int $quantity): ShipmentItems
    {
        $shipmentItem = new ShipmentItems();
        $shipmentItem->setShipmentId($shipment);
        $shipmentItem->setItemId($item);
        $shipmentItem->setQuantity($quantity);
        return $shipmentItem;
    }


}
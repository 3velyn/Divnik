<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ShipmentItems
 *
 * @ORM\Table(name="shipments_items")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ShipmentItemsRepository")
 */
class ShipmentItems
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var Shipment
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Shipment", inversedBy="items")
     * @ORM\JoinColumn(name="shipment_id", referencedColumnName="id")
     */
    private $shipmentId;

    /**
     * @var Jewellery
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Jewellery", inversedBy="shipments")
     * @ORM\JoinColumn(name="item_id", referencedColumnName="id")
     */
    private $itemId;

    /**
     * @var int
     *
     * @ORM\Column(name="quantity", type="integer")
     */
    private $quantity;

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set shipmentId.
     *
     * @param Shipment $shipment
     *
     * @return ShipmentItems
     */
    public function setShipmentId($shipment)
    {
        $this->shipmentId = $shipment;

        return $this;
    }

    /**
     * Get shipmentId.
     *
     * @return Shipment
     */
    public function getShipmentId()
    {
        return $this->shipmentId;
    }

    /**
     * Set jewelleryId.
     *
     * @param Jewellery $itemId
     *
     * @return ShipmentItems
     */
    public function setItemId($itemId)
    {
        $this->itemId = $itemId;

        return $this;
    }

    /**
     * Get jewelleryId.
     *
     * @return Jewellery
     */
    public function getItemId()
    {
        return $this->itemId;
    }

    /**
     * Set quantity.
     *
     * @param int $quantity
     *
     * @return ShipmentItems
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Get quantity.
     *
     * @return int
     */
    public function getQuantity()
    {
        return $this->quantity;
    }
}

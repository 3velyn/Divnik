<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Shipment
 *
 * @ORM\Table(name="shipments")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ShipmentRepository")
 */
class Shipment
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
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @var bool
     *
     * @ORM\Column(name="isShipped", type="boolean")
     */
    private $isShipped;


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
     * Set date.
     *
     * @param \DateTime $date
     *
     * @return Shipment
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date.
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set isShipped.
     *
     * @param bool $isShipped
     *
     * @return Shipment
     */
    public function setIsShipped($isShipped)
    {
        $this->isShipped = $isShipped;

        return $this;
    }

    /**
     * Get isShipped.
     *
     * @return bool
     */
    public function getIsShipped()
    {
        return $this->isShipped;
    }
}

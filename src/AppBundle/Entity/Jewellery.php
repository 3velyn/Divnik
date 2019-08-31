<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Jewellery
 *
 * @ORM\Table(name="jewelleries")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\JewelleryRepository")
 */
class Jewellery
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="price", type="decimal", precision=10, scale=2)
     */
    private $price;

    /**
     * @var string
     *
     * @ORM\Column(name="image", type="text")
     */
    private $image;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\ShipmentItems", mappedBy="itemId")
     */
    private $shipments;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Gem")
     * @ORM\JoinTable(name="jewellery_gem",
     *     joinColumns={@ORM\JoinColumn(name="jewellery_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="gem_id", referencedColumnName="id")})
     */
    private $gems;

    public function __construct()
    {
        $this->gems = new ArrayCollection();
        $this->shipments = new ArrayCollection();
    }

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
     * Set name.
     *
     * @param string $name
     *
     * @return Jewellery
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set price.
     *
     * @param string $price
     *
     * @return Jewellery
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price.
     *
     * @return string
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set image.
     *
     * @param string $image
     *
     * @return Jewellery
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image.
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }


    /**
     * @return ArrayCollection|Gem[]
     */
    public function getGems()
    {
        return $this->gems;
    }

    /**
     * @param Gem $gem
     * @return Jewellery
     */
    public function addGem(Gem $gem)
    {
        $this->gems[] = $gem;
        return $this;
    }

    /**
     * @param Gem $gem
     * @return Jewellery
     */
    public function removeGem(Gem $gem)
    {
        $this->gems->removeElement($gem);
        return $this;
    }

    /**
     * @return ArrayCollection|ShipmentItems[]
     */
    public function getShipments()
    {
        return $this->shipments;
    }

    /**
     * @param ArrayCollection $shipments
     */
    public function setShipments(ArrayCollection $shipments)
    {
        $this->shipments = $shipments;
    }

}

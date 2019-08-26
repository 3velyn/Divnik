<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Gem
 *
 * @ORM\Table(name="gems")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\GemRepository")
 */
class Gem
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
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="info", type="text")
     */
    private $info;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Jewellery", mappedBy="gems")
     */
    private $jewelleries;


    public function __construct()
    {
        $this->jewelleries = new ArrayCollection();
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
     * @return Gem
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
     * Set info.
     *
     * @param string $info
     *
     * @return Gem
     */
    public function setInfo($info)
    {
        $this->info = $info;

        return $this;
    }

    /**
     * Get info.
     *
     * @return string
     */
    public function getInfo()
    {
        return $this->info;
    }

    /**
     * @return ArrayCollection
     */
    public function getJewelleries()
    {
        return $this->jewelleries;
    }

    /**
     * @param ArrayCollection $jewelleries
     */
    public function setJewelleries(ArrayCollection $jewelleries)
    {
        $this->jewelleries = $jewelleries;
    }

}

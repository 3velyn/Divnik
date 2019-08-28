<?php


namespace AppBundle\Service\Jewelleries;


use AppBundle\Entity\Jewellery;
use Doctrine\Common\Collections\ArrayCollection;

interface JewelleryServiceInterface
{
    public function create(Jewellery $jewellery): bool;
    public function edit(Jewellery $jewellery): bool;
    public function delete(Jewellery $jewellery): bool;

    /**
     * @return ArrayCollection|Jewellery[]
     */
    public function getAll();
    public function getOne(int $id): ?Jewellery;
}
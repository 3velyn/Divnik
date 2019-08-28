<?php


namespace AppBundle\Service\Gems;


use AppBundle\Entity\Gem;
use Doctrine\Common\Collections\ArrayCollection;

interface GemServiceInterface
{
    public function create(Gem $gem): bool;
    public function edit(Gem $gem): bool;
    public function delete(Gem $gem): bool;

    /**
     * @return ArrayCollection|Gem[]
     */
    public function getAll();
    public function getOne(int $id): ?Gem;
    public function getOneByJewelleryId(int $id): ?Gem;
}
<?php


namespace AppBundle\Service\Cart;


use AppBundle\Entity\Cart;
use Doctrine\Common\Collections\ArrayCollection;

interface CartServiceInterface
{
    public function create(Cart $cart): bool;
    public function edit(Cart $cart): bool;
    public function delete(Cart $cart): bool;

    /**
     * @return ArrayCollection|Cart[]
     */
    public function getAll();

    /**
     * @param int $userId
     * @return ArrayCollection|Cart[]
     */
    public function getAllByUser(int $userId);
    public function findOneByUserIdAndItemId(int $userId, int $jewelleryId): ?Cart;
}
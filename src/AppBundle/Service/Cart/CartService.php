<?php


namespace AppBundle\Service\Cart;


use AppBundle\Entity\Cart;
use AppBundle\Repository\CartRepository;
use Doctrine\Common\Collections\ArrayCollection;

class CartService implements CartServiceInterface
{
    /**
     * @var CartRepository
     */
    private $cartRepository;

    public function __construct(CartRepository $cartRepository)
    {
        $this->cartRepository = $cartRepository;
    }

    public function create(Cart $cart): bool
    {
        return $this->cartRepository->insert($cart);
    }

    public function edit(Cart $cart): bool
    {
        return $this->cartRepository->update($cart);
    }

    public function delete(Cart $cart): bool
    {
        return $this->cartRepository->remove($cart);
    }

    /**
     * @return ArrayCollection|Cart[]
     */
    public function getAll()
    {
        return $this->cartRepository->findAll();
    }

    /**
     * @param int $userId
     * @return ArrayCollection|Cart[]
     */
    public function getAllByUser(int $userId)
    {
        return $this->cartRepository->findBy(['userId' => $userId]);
    }

    /**
     * @param int $userId
     * @param int $jewelleryId
     * @return Cart|null|object
     */
    public function findOneByUserIdAndItemId(int $userId, int $jewelleryId): ?Cart
    {
        return $this->cartRepository->findOneBy(['userId' => $userId, 'jewelleryId' => $jewelleryId]);
    }
}
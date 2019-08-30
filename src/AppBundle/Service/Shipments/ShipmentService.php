<?php


namespace AppBundle\Service\Shipments;


use AppBundle\Entity\Shipment;
use AppBundle\Entity\User;
use AppBundle\Repository\ShipmentRepository;
use AppBundle\Service\Users\UserServiceInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\AbstractQuery;
use Symfony\Component\Ldap\Adapter\ExtLdap\Query;
use Symfony\Component\Validator\Constraints\Date;

class ShipmentService implements ShipmentServiceInterface
{
    /**
     * @var ShipmentRepository
     */
    private $shipmentRepository;

    /**
     * @var UserServiceInterface
     */
    private $userService;

    public function __construct(ShipmentRepository $shipmentRepository,
                                UserServiceInterface $userService)
    {
        $this->shipmentRepository = $shipmentRepository;
        $this->userService = $userService;
    }

    public function create(Shipment $shipment): bool
    {
        $user = $this->userService->currentUser();
        $shipment->setUserId($user->getId());

        return $this->shipmentRepository->insert($shipment);
    }

    public function edit(Shipment $shipment): bool
    {
        return $this->shipmentRepository->update($shipment);
    }

    public function delete(Shipment $shipment): bool
    {
        return $this->shipmentRepository->remove($shipment);
    }

    /**
     * @return ArrayCollection|Shipment[]
     */
    public function getAll()
    {
        return $this->shipmentRepository->findAll();
    }

    /**
     * @return ArrayCollection|Shipment[]
     */
    public function getAllNotShipped()
    {
        return $this->shipmentRepository->findBy(['isShipped' => false], ['date' => 'DESC']);
    }

    /**
     * @param User $user
     * @return ArrayCollection|Shipment[]
     */
    public function getAllByUser(User $user)
    {
        return $this->shipmentRepository->findBy(['user' => $user]);
    }
}
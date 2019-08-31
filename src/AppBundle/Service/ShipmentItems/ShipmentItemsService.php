<?php


namespace AppBundle\Service\ShipmentItems;


use AppBundle\Entity\Jewellery;
use AppBundle\Entity\Shipment;
use AppBundle\Entity\ShipmentItems;
use AppBundle\Repository\ShipmentItemsRepository;
use Doctrine\Common\Collections\ArrayCollection;

class ShipmentItemsService implements ShipmentItemsServiceInterface
{
    /**
     * @var ShipmentItemsRepository
     */
    private $shipmentItemsRepository;

    public function __construct(ShipmentItemsRepository $shipmentItemsRepository)
    {
        $this->shipmentItemsRepository = $shipmentItemsRepository;
    }

    public function create(ShipmentItems $item): bool
    {
        return $this->shipmentItemsRepository->insert($item);
    }

    public function edit(ShipmentItems $item): bool
    {
        return $this->shipmentItemsRepository->update($item);
    }

    public function delete(ShipmentItems $item): bool
    {
        return $this->shipmentItemsRepository->remove($item);
    }

    /**
     * @param int $id
     * @return ArrayCollection|ShipmentItems[]
     */
    public function getAllByShipmentId(int $id)
    {
        return $this->shipmentItemsRepository->findBy(['shipmentId' => $id]);
    }

    /**
     * @param Shipment $shipment
     * @param Jewellery $item
     * @return ShipmentItems|object
     */
    public function getOneByShipmentIdAndItemId(Shipment $shipment, Jewellery $item)
    {
        return $this->shipmentItemsRepository->findOneBy(['shipmentId' => $shipment, 'itemId' => $item]);
    }

    /**
     * @param int $id
     * @return ArrayCollection|ShipmentItems[]
     */
    public function getAllByShipment(int $id)
    {
        $result = $this->shipmentItemsRepository->createQueryBuilder('items')
            ->innerJoin('AppBundle:Shipment', 'ship')
            ->andWhere('ship.id = ?1')
            ->andWhere('items.shipmentId = ?2')
            ->setParameter('1', $id)
            ->setParameter('2', $id)
            ->getQuery()
            ->execute();

        return $result;
    }
}
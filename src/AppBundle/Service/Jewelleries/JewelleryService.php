<?php


namespace AppBundle\Service\Jewelleries;


use AppBundle\Entity\Jewellery;
use AppBundle\Repository\JewelleryRepository;
use AppBundle\Service\Gems\GemServiceInterface;
use Doctrine\Common\Collections\ArrayCollection;

class JewelleryService implements JewelleryServiceInterface
{
    /**
     * @var JewelleryRepository
     */
    private $jewelleryRepository;

    /**
     * @var GemServiceInterface
     */
    private $gemService;

    public function __construct(JewelleryRepository $jewelleryRepository,
                                GemServiceInterface $gemService)
    {
        $this->jewelleryRepository = $jewelleryRepository;
        $this->gemService = $gemService;
    }

    public function create(Jewellery $jewellery): bool
    {
         return $this->jewelleryRepository->insert($jewellery);
    }

    public function edit(Jewellery $jewellery): bool
    {
        return $this->jewelleryRepository->update($jewellery);
    }

    public function delete(Jewellery $jewellery): bool
    {
        return $this->jewelleryRepository->remove($jewellery);
    }

    /**
     * @return ArrayCollection|Jewellery[]
     */
    public function getAll()
    {
        return $this->jewelleryRepository->findAll();
    }

    /**
     * @param int $id
     * @return Jewellery|null|object
     */
    public function getOne(int $id): ?Jewellery
    {
        return $this->jewelleryRepository->find($id);
    }
}
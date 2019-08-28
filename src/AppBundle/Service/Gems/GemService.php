<?php


namespace AppBundle\Service\Gems;


use AppBundle\Entity\Gem;
use AppBundle\Repository\GemRepository;
use Doctrine\Common\Collections\ArrayCollection;

class GemService implements GemServiceInterface
{

    /**
     * @var GemRepository
     */
    private $gemRepository;

    public function __construct(GemRepository $gemRepository)
    {
        $this->gemRepository = $gemRepository;
    }

    public function create(Gem $gem): bool
    {
        return $this->gemRepository->insert($gem);
    }

    public function edit(Gem $gem): bool
    {
        return $this->gemRepository->update($gem);
    }

    public function delete(Gem $gem): bool
    {
        return $this->gemRepository->remove($gem);
    }

    /**
     * @return ArrayCollection|Gem[]
     */
    public function getAll()
    {
        return $this->gemRepository->findBy([], ['name' => 'ASC']);
    }

    /**
     * @param int $id
     * @return Gem|null|object
     */
    public function getOne(int $id): ?Gem
    {
        return $this->gemRepository->find($id);

    }

    public function getOneByJewelleryId(int $id): ?Gem
    {
    }
}
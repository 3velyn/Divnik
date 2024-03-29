<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Gem;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;

/**
 * GemRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class GemRepository extends \Doctrine\ORM\EntityRepository
{
    public function __construct(EntityManagerInterface $em,
                                ClassMetadata $metadata = null)
    {
        parent::__construct($em,
            $metadata == null ?
                new ClassMetadata(Gem::class) : $metadata);
    }

    /**
     * @param Gem $gem
     * @return bool
     * @throws ORMException
     */
    public function insert(Gem $gem)
    {
        try {
            $this->_em->persist($gem);
            $this->_em->flush();
            return true;
        } catch (OptimisticLockException $e) {
            return false;
        }
    }

    /**
     * @param Gem $gem
     * @return bool
     * @throws ORMException
     */
    public function update(Gem $gem)
    {
        try {
            $this->_em->merge($gem);
            $this->_em->flush();
            return true;
        } catch (OptimisticLockException $e) {
            return false;
        }
    }

    /**
     * @param Gem $gem
     * @return bool
     * @throws ORMException
     */
    public function remove(Gem $gem)
    {
        try {
            $this->_em->remove($gem);
            $this->_em->flush();
            return true;
        } catch (OptimisticLockException $e) {
            return false;
        }
    }
}

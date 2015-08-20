<?php

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\CorePlugin\Model;

trait ManagerBehaviour
{
    /**
     * @var \Doctrine\Common\Persistence\ManagerRegistry
     */
    private $manager_registry = null;

    /**
     * @var \Doctrine\Common\Persistence\ObjectRepository|null
     */
    private $entity_repository = null;

    /**
     * @var \Doctrine\Common\Persistence\ObjectManager|null
     */
    private $entity_manager = null;

    /**
     * @var string
     */
    private $class;

    /**
     * @param \Doctrine\Common\Persistence\ManagerRegistry $manager_registry
     *
     * @return $this
     */
    protected function setManagerRegistry($manager_registry)
    {
        $this->manager_registry = $manager_registry;

        return $this;
    }

    /**
     * @return \Doctrine\Common\Persistence\ManagerRegistry
     */
    protected function getManagerRegistry()
    {
        return $this->manager_registry;
    }

    /**
     * @param string $class
     *
     * @return self
     */
    protected function setClass($class)
    {
        $this->class = $class;

        return $this;
    }

    /**
     * @return string
     */
    protected function getClass()
    {
        return $this->class;
    }

    /**
     * @return \Doctrine\Common\Persistence\ObjectManager|null
     */
    public function getEntityManager()
    {
        if (is_null($this->entity_manager)) {
            $this->entity_manager = $this->getManagerRegistry()->getManagerForClass($this->getClass());
        }

        return $this->entity_manager;
    }

    /**
     * @return \Doctrine\Common\Persistence\ObjectRepository
     */
    public function getEntityRepository()
    {
        if (is_null($this->entity_repository)) {
            $this->entity_repository = $this->getEntityManager()->getRepository($this->getClass());
        }

        return $this->entity_repository;
    }
}

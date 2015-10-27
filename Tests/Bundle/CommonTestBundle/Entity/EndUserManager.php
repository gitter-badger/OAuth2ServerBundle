<?php

namespace SpomkyLabs\Bundle\CommonTestBundle\Entity;

use Doctrine\Common\Persistence\ManagerRegistry;
use OAuth2\EndUser\EndUserInterface;
use OAuth2\EndUser\EndUserManagerInterface;

class EndUserManager implements EndUserManagerInterface
{
    private $entity_repository;
    private $entity_manager;
    private $class;

    public function __construct($class, ManagerRegistry $manager_registry)
    {
        $this->class = $class;
        $this->entity_manager = $manager_registry->getManagerForClass($class);
        $this->entity_repository = $this->entity_manager->getRepository($class);
    }

    public function createEndUser()
    {
        $class = $this->getClass();

        return new $class();
    }

    protected function getClass()
    {
        return $this->class;
    }

    public function saveEndUser(EndUserInterface $end_user)
    {
        $this->getEntityManager()->persist($end_user);
        $this->getEntityManager()->flush();
    }

    public function checkEndUserPasswordCredentials(EndUserInterface $end_user, $password)
    {
        if (!$end_user instanceof EndUser) {
            return false;
        }

        return $end_user->getPassword() === $password;
    }

    public function getEndUserByUsername($username)
    {
        return $this->getEntityRepository()->findOneBy(['username' => $username]);
    }

    public function getEndUserByPublicId($public_id)
    {
        return $this->getEntityRepository()->findOneBy(['public_id' => $public_id]);
    }

    public function getEndUser($public_id)
    {
        return $this->getEndUserByPublicId($public_id);
    }

    protected function getEntityRepository()
    {
        return $this->entity_repository;
    }

    protected function getEntityManager()
    {
        return $this->entity_manager;
    }
}

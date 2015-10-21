<?php

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\ClientManagerSupervisorPlugin\Model;

use SpomkyLabs\OAuth2ServerBundle\Plugin\CorePlugin\Model\ResourceOwnerManagerBehaviour;

trait ClientManagerBehaviour
{
    use ResourceOwnerManagerBehaviour;

    /**
     * @return string
     */
    abstract protected function getClass();

    /**
     * @return \Doctrine\Common\Persistence\ObjectRepository
     */
    abstract protected function getEntityRepository();

    /**
     * @return \Doctrine\Common\Persistence\ObjectManager
     */
    abstract protected function getEntityManager();

    /**
     * @param string $public_id
     *
     * @return object
     */
    public function getClient($public_id)
    {
        if (!$this->isPublicIdSupported($public_id)) {
            return;
        }
        $client = $this->getEntityRepository()->findOneBy(['public_id' => $public_id]);

        return $client;
    }

    /**
     * @return \SpomkyLabs\OAuth2ServerBundle\Plugin\ClientManagerSupervisorPlugin\Model\ClientInterface
     */
    public function createClient()
    {
        $class = $this->getClass();

        return new $class();
    }

    /**
     * @param \SpomkyLabs\OAuth2ServerBundle\Plugin\ClientManagerSupervisorPlugin\Model\ClientInterface $client
     *
     * @return self
     */
    public function saveClient(ClientInterface $client)
    {
        $this->getEntityManager()->persist($client);
        $this->getEntityManager()->flush();

        return $this;
    }
}

<?php

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\ClientManagerSupervisorPlugin\Model;

use SpomkyLabs\OAuth2ServerBundle\Plugin\CorePlugin\Model\ManagerBehaviour;
use SpomkyLabs\OAuth2ServerBundle\Plugin\CorePlugin\Model\ResourceOwnerManagerBehaviour;

trait ClientManagerBehaviour
{
    use ResourceOwnerManagerBehaviour;
    use ManagerBehaviour;

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
        /**
         * @var $client \SpomkyLabs\OAuth2ServerBundle\Plugin\ClientManagerSupervisorPlugin\Model\ClientInterface
         */
        $client = new $class();

        if (!empty($this->getPrefix())) {
            $client->setPublicId($this->getPrefix().$client->getPublicId());
        }

        return$client;
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

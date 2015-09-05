<?php

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\JWTBearerPlugin\Model;

use Doctrine\Common\Persistence\ManagerRegistry;
use OAuth2\Client\JWTClientManager as Base;
use SpomkyLabs\OAuth2ServerBundle\Plugin\CorePlugin\Model\ClientManagerBehaviour;
use SpomkyLabs\OAuth2ServerBundle\Plugin\CorePlugin\Model\ManagerBehaviour;
use SpomkyLabs\Service\Jose;

class JWTClientManager extends Base implements JWTClientManagerInterface
{
    use ManagerBehaviour;
    use ClientManagerBehaviour {
        saveClient as saveClientTrait;
    }

    /**
     * @param string                                       $class
     * @param \Doctrine\Common\Persistence\ManagerRegistry $manager_registry
     * @param array                                        $keys
     */
    public function __construct(
        $class,
        ManagerRegistry $manager_registry,
        array $keys
    ) {
        $this->setClass($class);
        $this->setManagerRegistry($manager_registry);
        $this->loadKeys($keys);
    }

    /**
     * @param array $keys
     */
    protected function loadKeys(array $keys)
    {
        $prepared = [];
        foreach($keys as $id=>$data) {
            $key = [
                'kid' => $id,
                'kty' => $data['type'],
            ];
            $prepared[] = array_merge($key, $data['data']);
        }

        $this->setPrivateKeySet($prepared);
    }

    /**
     * @return string
     */
    protected function getPrefix()
    {
        return 'JWT-';
    }

    /**
     * @return string
     */
    protected function getSuffix()
    {
        return '';
    }

    /**
     * @param \SpomkyLabs\OAuth2ServerBundle\Plugin\JWTBearerPlugin\Model\JWTClientInterface $client
     *
     * @return self
     */
    public function saveClient(JWTClientInterface $client)
    {
        $this->saveClientTrait($client);

        return $this;
    }
    /**
     * @return \Jose\JWKSetManagerInterface
     */
    public function getKeySetManager()
    {
        $jose = Jose::getInstance();

        return $jose->getKeysetManager();
    }

    /**
     * @return \Jose\LoaderInterface
     */
    public function getJWTLoader()
    {
        $jose = Jose::getInstance();

        return $jose->getLoader();
    }
}

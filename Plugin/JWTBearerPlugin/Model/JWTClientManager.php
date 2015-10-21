<?php

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\JWTBearerPlugin\Model;

use Doctrine\Common\Persistence\ManagerRegistry;
use Jose\JWKSetManagerInterface;
use Jose\LoaderInterface;
use OAuth2\Client\JWTClientManager as Base;
use SpomkyLabs\OAuth2ServerBundle\Plugin\ClientManagerSupervisorPlugin\Model\ClientManagerBehaviour;
use SpomkyLabs\OAuth2ServerBundle\Plugin\CorePlugin\Model\ManagerBehaviour;

class JWTClientManager extends Base implements JWTClientManagerInterface
{
    /**
     * @var \Jose\JWKSetManagerInterface
     */
    private $keyset_manager;

    /**
     * @var \Jose\LoaderInterface
     */
    private $loader;

    use ManagerBehaviour;
    use ClientManagerBehaviour {
        saveClient as saveClientTrait;
    }

    /**
     * @param                                              $class
     * @param \Doctrine\Common\Persistence\ManagerRegistry $manager_registry
     * @param array                                        $keys
     * @param \Jose\LoaderInterface                        $loader
     * @param \Jose\JWKSetManagerInterface                 $keyset_manager
     */
    public function __construct(
        $class,
        ManagerRegistry $manager_registry,
        array $keys,
        LoaderInterface $loader,
        JWKSetManagerInterface $keyset_manager
    ) {
        $this->setClass($class);
        $this->setManagerRegistry($manager_registry);
        $this->loader = $loader;
        $this->keyset_manager = $keyset_manager;
        $this->loadKeys($keys);
    }

    /**
     * @param array $keys
     */
    protected function loadKeys(array $keys)
    {
        $prepared = ['keys' => []];
        foreach ($keys as $id => $data) {
            $data['kid'] = $id;
            $prepared['keys'][] = $data;
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
        return $this->keyset_manager;
    }

    /**
     * @return \Jose\LoaderInterface
     */
    public function getJWTLoader()
    {
        return $this->loader;
    }
}

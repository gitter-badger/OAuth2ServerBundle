<?php

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\PublicClientPlugin\Model;

use Doctrine\Common\Persistence\ManagerRegistry;
use OAuth2\Client\PublicClientManager as BaseManager;
use Psr\Http\Message\ServerRequestInterface;
use SpomkyLabs\OAuth2ServerBundle\Plugin\ClientManagerSupervisorPlugin\Model\ClientManagerBehaviour;

class PublicClientManager extends BaseManager implements PublicClientManagerInterface
{
    use ClientManagerBehaviour;

    /**
     * @param string                                       $class
     * @param string                                       $prefix
     * @param \Doctrine\Common\Persistence\ManagerRegistry $manager_registry
     */
    public function __construct($class, $prefix, ManagerRegistry $manager_registry)
    {
        $this->setPrefix($prefix);
        $this->setClass($class);
        $this->setManagerRegistry($manager_registry);
    }

    /**
     * {@inheritdoc}
     */
    protected function findClientMethods()
    {
        return [
            'findClientInRequestHeader',
        ];
    }

    /**
     * Public client are identified with header name 'X-OAuth2-Public-Client-ID' in the request.
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     *
     * @return string|null
     */
    protected function findClientInRequestHeader(ServerRequestInterface $request)
    {
        $header = $request->getHeader('X-OAuth2-Public-Client-ID');

        return count($header) === 0 ? null : $header[0];
    }
}

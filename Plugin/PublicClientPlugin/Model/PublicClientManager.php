<?php

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\PublicClientPlugin\Model;

use Doctrine\Common\Persistence\ManagerRegistry;
use OAuth2\Client\PublicClientManager as BaseManager;
use OAuth2\Exception\ExceptionManagerInterface;
use Psr\Http\Message\ServerRequestInterface;
use SpomkyLabs\OAuth2ServerBundle\Plugin\CorePlugin\Model\ClientManagerBehaviour;
use SpomkyLabs\OAuth2ServerBundle\Plugin\CorePlugin\Model\ManagerBehaviour;
use Symfony\Component\HttpFoundation\Request;

class PublicClientManager extends BaseManager implements PublicClientManagerInterface
{
    use ManagerBehaviour;
    use ClientManagerBehaviour;

    /**
     * @param string                                       $class
     * @param \Doctrine\Common\Persistence\ManagerRegistry $manager_registry
     */
    public function __construct(
        $class,
        ManagerRegistry $manager_registry
    ) {
        $this->setClass($class);
        $this->setManagerRegistry($manager_registry);
    }

    protected function getPrefix()
    {
        return 'PUBLIC-';
    }

    protected function getSuffix()
    {
        return '';
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

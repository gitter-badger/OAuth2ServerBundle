<?php

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\PublicClientPlugin\Model;

use Doctrine\Common\Persistence\ManagerRegistry;
use OAuth2\Client\PublicClientManager as BaseManager;
use OAuth2\Exception\ExceptionManagerInterface;
use SpomkyLabs\OAuth2ServerBundle\Plugin\CorePlugin\Model\ClientManagerBehaviour;
use SpomkyLabs\OAuth2ServerBundle\Plugin\CorePlugin\Model\ManagerBehaviour;
use Symfony\Component\HttpFoundation\Request;

class PublicClientManager extends BaseManager implements PublicClientManagerInterface
{
    use ManagerBehaviour;
    use ClientManagerBehaviour;

    /**
     * @var \OAuth2\Exception\ExceptionManagerInterface
     */
    private $exception_manager;

    /**
     * @param string                                       $class
     * @param \Doctrine\Common\Persistence\ManagerRegistry $manager_registry
     * @param \OAuth2\Exception\ExceptionManagerInterface  $exception_manager
     */
    public function __construct(
        $class,
        ManagerRegistry $manager_registry,
        ExceptionManagerInterface $exception_manager
    ) {
        $this->setClass($class);
        $this->setManagerRegistry($manager_registry);
        $this->exception_manager = $exception_manager;
    }

    /**
     * {@inheritdoc}
     */
    protected function getExceptionManager()
    {
        return $this->exception_manager;
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
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return null|string
     */
    protected function findClientInRequestHeader(Request $request)
    {
        $header = $request->headers->get('X-OAuth2-Public-Client-ID');

        return empty($header) ? null : $header;
    }
}

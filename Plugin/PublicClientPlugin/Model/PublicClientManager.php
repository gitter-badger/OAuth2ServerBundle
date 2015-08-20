<?php

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\PublicClientPlugin\Model;

use OAuth2\Client\PublicClientManager as BaseManager;
use OAuth2\Exception\ExceptionManagerInterface;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;

class PublicClientManager extends BaseManager implements PublicClientManagerInterface
{
    /**
     * @var \Doctrine\Common\Persistence\ObjectRepository
     */
    private $entity_repository;

    /**
     * @var \Doctrine\Common\Persistence\ObjectManager
     */
    private $entity_manager;

    /**
     * @var \OAuth2\Exception\ExceptionManagerInterface
     */
    private $exception_manager;

    /**
     * @var string
     */
    private $class;

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
        $this->class = $class;
        $this->exception_manager = $exception_manager;
        $this->entity_manager = $manager_registry->getManagerForClass($class);
        $this->entity_repository = $this->entity_manager->getRepository($class);
    }

    /**
     * {@inheritdoc}
     */
    protected function getExceptionManager()
    {
        return $this->exception_manager;
    }

    /**
     * @return string
     */
    protected function getClass()
    {
        return $this->class;
    }

    /**
     * {@inheritdoc}
     */
    public function getClient($public_id)
    {
        $client = $this->getEntityRepository()->findOneBy(array('public_id' => $public_id));

        return $client;
    }

    /**
     * @return \Doctrine\Common\Persistence\ObjectRepository
     */
    protected function getEntityRepository()
    {
        return $this->entity_repository;
    }

    /**
     * @return \Doctrine\Common\Persistence\ObjectManager
     */
    protected function getEntityManager()
    {
        return $this->entity_manager;
    }

    /**
     * {@inheritdoc}
     */
    protected function findClientMethods()
    {
        return array(
            'findClientInRequestHeader',
        );
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

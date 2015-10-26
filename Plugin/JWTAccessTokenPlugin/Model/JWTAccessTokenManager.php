<?php

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\JWTAccessTokenPlugin\Model;

use OAuth2\Token\JWTAccessTokenManager as BaseManager;
use SpomkyLabs\OAuth2ServerBundle\Plugin\CorePlugin\Service\CleanerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class JWTAccessTokenManager extends BaseManager implements JWTAccessTokenManagerInterface, CleanerInterface
{
    /**
     * @var \Symfony\Component\EventDispatcher\EventDispatcherInterface|null
     */
    private $event_dispatcher;

    /**
     * @param \Symfony\Component\EventDispatcher\EventDispatcherInterface|null $event_dispatcher
     */
    public function __construct(
        EventDispatcherInterface $event_dispatcher = null
    ) {
        $this->event_dispatcher = $event_dispatcher;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteExpired()
    {
        return 0;
    }

    /**
     * {@inheritdoc}
     */
    public function clean()
    {
        $result = $this->deleteExpired();
        if (0 < $result) {
            return ['expired access token(s)' => $result];
        }

        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'JWT Access Token Manager';
    }
}

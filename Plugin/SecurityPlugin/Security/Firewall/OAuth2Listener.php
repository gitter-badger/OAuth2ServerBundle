<?php

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\SecurityPlugin\Security\Firewall;

use OAuth2\Behaviour\HasAccessTokenTypeManager;
use SpomkyLabs\OAuth2ServerBundle\Plugin\SecurityPlugin\Security\Authentication\Token\OAuth2Token;
use Symfony\Bridge\PsrHttpMessage\Factory\DiactorosFactory;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Firewall\ListenerInterface;

class OAuth2Listener implements ListenerInterface
{
    use HasAccessTokenTypeManager;

    /**
     * @var \Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface
     */
    private $token_storage;

    /**
     * @var \Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface
     */
    private $authentication_manager;

    /**
     * @param \Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface $token_storage
     * @param \Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface      $authentication_manager
     */
    public function __construct(TokenStorageInterface $token_storage, AuthenticationManagerInterface $authentication_manager)
    {
        $this->token_storage = $token_storage;
        $this->authentication_manager = $authentication_manager;
    }

    /**
     * {@inheritdoc}
     */
    public function handle(GetResponseEvent $event)
    {
        $factory = new DiactorosFactory();
        $request = $factory->createRequest($event->getRequest());
        $token_id = $this->getAccessTokenTypeManager()->findAccessToken($request);

        if (null === $token_id) {
            return;
        }

        try {
            $token = new OAuth2Token();
            $token->setToken($token_id);

            $result = $this->authentication_manager->authenticate($token);

            $this->token_storage->setToken($result);
        } catch (AuthenticationException $e) {
            if (null !== $e->getPrevious()) {
                $e = $e->getPrevious();
            }
            $response = new Response($e->getMessage(), 401);
            $event->setResponse($response);
        }
    }
}

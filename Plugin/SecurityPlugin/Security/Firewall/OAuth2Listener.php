<?php

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\SecurityPlugin\Security\Firewall;

use OAuth2\Token\AccessTokenManagerInterface;
use OAuth2\Token\AccessTokenTypeManagerInterface;
use SpomkyLabs\OAuth2ServerBundle\Plugin\SecurityPlugin\Security\Authentication\Token\OAuth2Token;
use Symfony\Bridge\PsrHttpMessage\Factory\DiactorosFactory;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\Security\Http\Firewall\ListenerInterface;

class OAuth2Listener implements ListenerInterface
{
    /**
     * @var \Symfony\Component\Security\Core\SecurityContextInterface
     */
    private $security_context;

    /**
     * @var \Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface
     */
    private $authentication_manager;

    /**
     * @var \OAuth2\Token\AccessTokenTypeManagerInterface
     */
    private $access_token_type_manager;

    /**
     * @var \OAuth2\Token\AccessTokenManagerInterface
     */
    private $access_token_manager;

    /**
     * @param \Symfony\Component\Security\Core\SecurityContextInterface                      $security_context
     * @param \Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface $authentication_manager
     * @param \OAuth2\Token\AccessTokenTypeManagerInterface                                  $access_token_type_manager
     * @param \OAuth2\Token\AccessTokenManagerInterface                                      $access_token_manager
     */
    public function __construct(SecurityContextInterface $security_context, AuthenticationManagerInterface $authentication_manager, AccessTokenTypeManagerInterface $access_token_type_manager, AccessTokenManagerInterface $access_token_manager)
    {
        $this->security_context = $security_context;
        $this->authentication_manager = $authentication_manager;
        $this->access_token_type_manager = $access_token_type_manager;
        $this->access_token_manager = $access_token_manager;
    }

    /**
     * @param \Symfony\Component\HttpKernel\Event\GetResponseEvent $event
     */
    public function handle(GetResponseEvent $event)
    {
        $factory = new DiactorosFactory();
        $request = $factory->createRequest($event->getRequest());
        $access_oauth_token = $this->access_token_type_manager->findAccessToken($request);

        if (is_null($access_oauth_token)) {
            return;
        }

        $token = new OAuth2Token();
        $token->setToken($access_oauth_token);

        try {
            $return_value = $this->authentication_manager->authenticate($token);

            if ($return_value instanceof TokenInterface) {
                return $this->security_context->setToken($return_value);
            }

            if ($return_value instanceof Response) {
                $event->setResponse($return_value);

                return;
            }
        } catch (AuthenticationException $e) {
            if (null !== $e->getPrevious()) {
                $e = $e->getPrevious();
            }
            $response = new Response($e->getMessage(), $e->getCode());
            $event->setResponse($response);
        }
    }
}

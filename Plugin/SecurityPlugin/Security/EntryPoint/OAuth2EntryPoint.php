<?php

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\SecurityPlugin\Security\EntryPoint;

use OAuth2\Token\AccessTokenTypeManagerInterface;
use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\HttpFoundation\Request;
use OAuth2\Exception\ExceptionManagerInterface;

class OAuth2EntryPoint implements AuthenticationEntryPointInterface
{
    /**
     * @var \OAuth2\Exception\ExceptionManagerInterface
     */
    protected $exception_manager;

    /**
     * @var \OAuth2\Token\AccessTokenTypeManagerInterface
     */
    protected $access_token_type_manager;

    /**
     * @param \OAuth2\Exception\ExceptionManagerInterface   $exception_manager
     * @param \OAuth2\Token\AccessTokenTypeManagerInterface $access_token_type_manager
     */
    public function __construct(ExceptionManagerInterface $exception_manager, AccessTokenTypeManagerInterface $access_token_type_manager)
    {
        $this->exception_manager = $exception_manager;
        $this->access_token_type_manager = $access_token_type_manager;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request                               $request
     * @param \Symfony\Component\Security\Core\Exception\AuthenticationException|null $authException
     *
     * @return mixed
     */
    public function start(Request $request, AuthenticationException $authException = null)
    {
        $params = array(
            'scheme' => $this->access_token_type_manager->getDefaultAccessTokenType()->getScheme(),
        );

        $exception = $this->exception_manager->getException(
            'Authenticate',
            'access_denied',
            'OAuth2 authentication required',
            $params
        );

        return $exception->getHttpResponse();
    }
}

<?php

/*
 * The MIT License (MIT)
 *
 * Copyright (c) 2014-2015 Spomky-Labs
 *
 * This software may be modified and distributed under the terms
 * of the MIT license.  See the LICENSE file for details.
 */

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\SecurityPlugin\Security\Authentication\Provider;

use OAuth2\Behaviour\HasAccessTokenManager;
use OAuth2\Behaviour\HasAccessTokenTypeManager;
use OAuth2\Behaviour\HasClientManagerSupervisor;
use OAuth2\Behaviour\HasEndUserManager;
use OAuth2\Client\ClientInterface;
use OAuth2\EndUser\EndUserInterface;
use OAuth2\Exception\BaseExceptionInterface;
use OAuth2\ResourceOwner\ResourceOwnerInterface;
use OAuth2\Token\AccessTokenInterface;
use SpomkyLabs\OAuth2ServerBundle\Plugin\SecurityPlugin\Security\Authentication\Token\OAuth2Token;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Provider\AuthenticationProviderInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AccountStatusException;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class OAuth2Provider implements AuthenticationProviderInterface
{
    use HasEndUserManager;
    use HasClientManagerSupervisor;
    use HasAccessTokenManager;
    use HasAccessTokenTypeManager;

    /**
     * @var \Symfony\Component\Security\Core\User\UserProviderInterface
     */
    protected $user_provider;

    /**
     * @var \Symfony\Component\Security\Core\User\UserCheckerInterface
     */
    protected $user_checker;

    /**
     * @param \Symfony\Component\Security\Core\User\UserProviderInterface $user_provider
     * @param \Symfony\Component\Security\Core\User\UserCheckerInterface  $user_checker
     */
    public function __construct(
        UserProviderInterface $user_provider,
        UserCheckerInterface $user_checker
    ) {
        $this->user_provider = $user_provider;
        $this->user_checker = $user_checker;
    }

    /**
     * {@inheritdoc}
     */
    public function authenticate(TokenInterface $token, Request $request = null)
    {
        if (!$this->supports($token)) {
            return;
        }

        /*
         * @var $token \SpomkyLabs\OAuth2ServerBundle\Plugin\SecurityPlugin\Security\Authentication\Token\OAuth2Token
         */
        $token_id = $token->getToken();

        $access_token = $this->getAccessTokenManager()->getAccessToken($token_id);
        if (!$access_token instanceof AccessTokenInterface) {
            throw new BadCredentialsException('Access token is missing');
        }
        if (false === $this->getAccessTokenManager()->isAccessTokenValid($access_token)) {
            throw new BadCredentialsException('Access token is not valid');
        }
        $token->setAccessToken($access_token);

        try {
            $resource_owner = $this->getResourceOwner($access_token);
            $this->checkResourceOwner($resource_owner);
            $token->setResourceOwner($resource_owner);
            $client = $this->getClient($access_token);
            $token->setClient($client);
            $token->setAuthenticated(true);

            return $token;
        } catch (BaseExceptionInterface $e) {
            throw new AuthenticationException($e->getDescription(), $e->getHttpCode(), $e);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function supports(TokenInterface $token)
    {
        return $token instanceof OAuth2Token;
    }

    /**
     * @param \OAuth2\ResourceOwner\ResourceOwnerInterface $resource_owner
     * 
     * @throws \OAuth2\Exception\BaseExceptionInterface
     */
    private function checkResourceOwner(ResourceOwnerInterface $resource_owner)
    {
        if ($resource_owner instanceof UserInterface) {
            try {
                $this->user_checker->checkPostAuth($resource_owner);
            } catch (AccountStatusException $e) {
                throw new BadCredentialsException($e->getMessage());
            }
        }
    }

    /**
     * @param \OAuth2\Token\AccessTokenInterface $access_token
     * 
     * @throws \OAuth2\Exception\BaseExceptionInterface
     * 
     * @return \OAuth2\Client\ClientInterface
     */
    private function getClient(AccessTokenInterface $access_token)
    {
        $client = $this->getClientManagerSupervisor()->getClient($access_token->getClientPublicId());
        if (null === $client) {
            throw new BadCredentialsException('Unknown client');
        }

        return $client;
    }

    /**
     * @param \OAuth2\Token\AccessTokenInterface $access_token
     *
     * @throws \OAuth2\Exception\BaseExceptionInterface
     *
     * @return \OAuth2\Client\ClientInterface|\OAuth2\EndUser\EndUserInterface
     */
    private function getResourceOwner(AccessTokenInterface $access_token)
    {
        $resource_owner = $this->getClientManagerSupervisor()->getClient($access_token->getResourceOwnerPublicId());
        if ($resource_owner instanceof ClientInterface) {
            return $resource_owner;
        }

        $resource_owner = $this->getEndUserManager()->getEndUser($access_token->getResourceOwnerPublicId());
        if (!$resource_owner instanceof EndUserInterface) {
            throw new BadCredentialsException('Unknown resource owner');
        }

        return $resource_owner;
    }
}

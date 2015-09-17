<?php

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\SecurityPlugin\Security\Authentication\Provider;

use OAuth2\Exception\BaseExceptionInterface;
use OAuth2\Exception\ExceptionManagerInterface;
use OAuth2\Token\AccessTokenManagerInterface;
use OAuth2\Token\AccessTokenTypeManagerInterface;
use SpomkyLabs\OAuth2ServerBundle\Plugin\SecurityPlugin\Security\Authentication\Token\OAuth2Token;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Provider\AuthenticationProviderInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AccountStatusException;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class OAuth2Provider implements AuthenticationProviderInterface
{
    /**
     * @var \Symfony\Component\Security\Core\User\UserProviderInterface
     */
    protected $user_provider;

    /**
     * @var \Symfony\Component\Security\Core\User\UserCheckerInterface
     */
    protected $user_checker;

    /**
     * @var \OAuth2\Exception\ExceptionManagerInterface
     */
    protected $exception_manager;

    /**
     * @var \OAuth2\Token\AccessTokenManagerInterface
     */
    protected $access_token_manager;

    /**
     * @var \OAuth2\Token\AccessTokenTypeManagerInterface
     */
    protected $access_token_type_manager;

    /**
     * @param \Symfony\Component\Security\Core\User\UserProviderInterface $user_provider
     * @param \Symfony\Component\Security\Core\User\UserCheckerInterface  $user_checker
     * @param \OAuth2\Exception\ExceptionManagerInterface                 $exception_manager
     * @param \OAuth2\Token\AccessTokenTypeManagerInterface               $access_token_type_manager
     * @param \OAuth2\Token\AccessTokenManagerInterface                   $access_token_manager
     */
    public function __construct(
        UserProviderInterface $user_provider,
        UserCheckerInterface $user_checker,
        ExceptionManagerInterface $exception_manager,
        AccessTokenTypeManagerInterface $access_token_type_manager,
        AccessTokenManagerInterface $access_token_manager
    ) {
        $this->user_provider = $user_provider;
        $this->user_checker = $user_checker;
        $this->exception_manager = $exception_manager;
        $this->access_token_type_manager = $access_token_type_manager;
        $this->access_token_manager = $access_token_manager;
    }

    /**
     * {@inheritdoc}
     */
    public function authenticate(TokenInterface $token, Request $request = null)
    {
        if (!$this->supports($token) || null === $request) {
            return;
        }

        try {
            $access_token = $token->getToken();

            if ($access_token = $this->access_token_manager->isAccessTokenValid($access_token)) {
                $scope = $access_token->getScope();
                $resource_owner = $access_token->getResourceOwner();

                //$roles = (null !== $resource_owner) ? $resource_owner->getRoles() : array();

                /*if (!empty($scope)) {
                    foreach (explode(' ', $scope) as $role) {
                        $roles[] = 'ROLE_'.strtoupper($role);
                    }
                }*/

                $token = new OAuth2Token($roles);
                $token->setAuthenticated(true);
                $token->setToken($access_token);

                if (null !== $resource_owner) {
                    try {
                        $this->user_checker->checkPostAuth($resource_owner);
                    } catch (AccountStatusException $e) {
                        $params = [
                            'scheme' => $this->access_token_type_manager->getDefaultAccessTokenType()->getScheme(),
                        ];
                        //Add scope here if defined

                        throw $this->exception_manager->getException(
                            'Authenticate',
                            'access_denied',
                            $e->getMessage(),
                            $params
                        );
                    }

                    //$token->setResourceOwner($resource_owner);
                }

                return $token;
            }
        } catch (BaseExceptionInterface $e) {
            throw new AuthenticationException('OAuth2 authentication failed', 0, $e);
        }
        throw new AuthenticationException('OAuth2 authentication failed');
    }

    /**
     * {@inheritdoc}
     */
    public function supports(TokenInterface $token)
    {
        return $token instanceof OAuth2Token;
    }
}

<?php

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\SecurityPlugin\Security\Authentication\Provider;

use OAuth2\Behaviour\HasAccessTokenManager;
use OAuth2\Behaviour\HasAccessTokenTypeManager;
use OAuth2\Behaviour\HasClientManagerSupervisor;
use OAuth2\Behaviour\HasEndUserManager;
use OAuth2\Behaviour\HasExceptionManager;
use OAuth2\Client\ClientInterface;
use OAuth2\Exception\BaseExceptionInterface;
use OAuth2\Exception\ExceptionManagerInterface;
use OAuth2\ResourceOwner\ResourceOwnerInterface;
use OAuth2\Token\AccessTokenInterface;
use SpomkyLabs\OAuth2ServerBundle\Plugin\SecurityPlugin\Security\Authentication\Token\OAuth2Token;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Provider\AuthenticationProviderInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AccountStatusException;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class OAuth2Provider implements AuthenticationProviderInterface
{
    use HasExceptionManager;
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
            throw new AuthenticationException('Unknown access token');
        }
        if (false === $this->getAccessTokenManager()->isAccessTokenValid($access_token)) {
            throw new AuthenticationException('Access token is not valid');
        }
        $token->setAccessToken($access_token);

        try {
            $resource_owner = $this->getResourceOwner($access_token->getResourceOwnerPublicId());
            if (null === $resource_owner) {
                throw $this->createException('Unknown resource owner', $access_token);
            }
            $this->checkResourceOwner($resource_owner, $access_token);
            $token->setResourceOwner($resource_owner);

            $client = $this->getClientManagerSupervisor()->getClient($access_token->getClientPublicId());
            if (null === $client) {
                throw $this->createException('Unknown client', $access_token);
            }
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

    private function checkResourceOwner(ResourceOwnerInterface $resource_owner, AccessTokenInterface $access_token)
    {
        if ($resource_owner instanceof UserInterface) {
            try {
                $this->user_checker->checkPostAuth($resource_owner);
            } catch (AccountStatusException $e) {
                throw $this->createException($e->getMessage(), $access_token);
            }
        }
    }

    private function getResourceOwner($resource_owner_public_id)
    {
        $r_o = $this->getClientManagerSupervisor()->getClient($resource_owner_public_id);
        if ($r_o instanceof ClientInterface) {
            return $r_o;
        }

        return $this->getEndUserManager()->getEndUser($resource_owner_public_id);
    }

    private function createException($message, AccessTokenInterface $access_token)
    {
        $params = [
            'scheme' => $this->getAccessTokenTypeManager()->getDefaultAccessTokenType()->getScheme(),
        ];
        if (!empty($access_token->getScope())) {
            $params['scope'] = implode(' ', $access_token->getScope());
        }

        return $this->getExceptionManager()->getException(
            ExceptionManagerInterface::AUTHENTICATE,
            ExceptionManagerInterface::ACCESS_DENIED,
            $message,
            $params
        );
    }
}

<?php

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\SecurityPlugin\Annotation\Checker;

use OAuth2\Client\ClientInterface;
use OAuth2\Client\ConfidentialClientInterface;
use OAuth2\Client\RegisteredClientInterface;
use OAuth2\EndUser\EndUserInterface;
use OAuth2\ResourceOwner\ResourceOwnerInterface;
use SpomkyLabs\OAuth2ServerBundle\Plugin\SecurityPlugin\Security\Authentication\Token\OAuth2Token;
use SpomkyLabs\OAuth2ServerBundle\Plugin\SecurityPlugin\Annotation\OAuth2;

class ResourceOwnerTypeChecker implements CheckerInterface
{
    /**
     * {@inheritdoc}
     */
    public function check(OAuth2Token $token, OAuth2 $configuration)
    {
        if (null === $configuration->getResourceOwnerType()) {
            return;
        }

        $result = $this->isTypeValid($configuration->getResourceOwnerType(), $token->getResourceOwner());
        if (false === $result) {
            return 'Bad resource owner type';
        }
    }

    /**
     * @param string                                       $type
     * @param \OAuth2\ResourceOwner\ResourceOwnerInterface $client
     *
     * @return bool
     */
    private function isTypeValid($type, ResourceOwnerInterface $client)
    {
        switch ($type) {
            case 'end_user':
                return $client instanceof EndUserInterface;
            case 'client':
                return $client instanceof ClientInterface;
            case 'registered_client':
                return $client instanceof RegisteredClientInterface;
            case 'confidential_client':
                return $client instanceof ConfidentialClientInterface;
            case 'public_client':
                return $client instanceof RegisteredClientInterface && !$client instanceof ConfidentialClientInterface;
            case 'unregistered_client':
                return $client instanceof ClientInterface && !$client instanceof RegisteredClientInterface;
            default:
                return $type === $client->getType();
        }
    }
}

<?php

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\SecurityPlugin\Annotation\Checker;

use SpomkyLabs\OAuth2ServerBundle\Plugin\SecurityPlugin\Security\Authentication\Token\OAuth2Token;
use SpomkyLabs\OAuth2ServerBundle\Plugin\SecurityPlugin\Annotation\OAuth2;

class ClientPublicIdChecker implements CheckerInterface
{
    /**
     * {@inheritdoc}
     */
    public function check(OAuth2Token $token, OAuth2 $configuration)
    {
        if (null === $configuration->getClientPublicId()) {
            return true;
        }

        if ($configuration->getClientPublicId() !== $token->getClient()->getPublicId()) {
            return 'Client not authorized';
        }

        return true;
    }
}

<?php

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\SecurityPlugin\Annotation\Checker;

use SpomkyLabs\OAuth2ServerBundle\Plugin\SecurityPlugin\Annotation\OAuth2;
use SpomkyLabs\OAuth2ServerBundle\Plugin\SecurityPlugin\Security\Authentication\Token\OAuth2Token;

class ClientPublicIdChecker implements CheckerInterface
{
    /**
     * {@inheritdoc}
     */
    public function check(OAuth2Token $token, OAuth2 $configuration)
    {
        if (null === $configuration->getClientPublicId()) {
            return;
        }

        if ($configuration->getClientPublicId() !== $token->getClient()->getPublicId()) {
            return 'Client not authorized';
        }
    }
}

<?php

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\SecurityPlugin\Annotation\Checker;

use SpomkyLabs\OAuth2ServerBundle\Plugin\SecurityPlugin\Security\Authentication\Token\OAuth2Token;
use SpomkyLabs\OAuth2ServerBundle\Plugin\SecurityPlugin\Annotation\OAuth2;

class ResourceOwnerPublicIdChecker implements CheckerInterface
{
    /**
     * {@inheritdoc}
     */
    public function check(OAuth2Token $token, OAuth2 $configuration)
    {
        if (null === $configuration->getResourceOwnerPublicId()) {
            return;
        }

        if ($configuration->getResourceOwnerPublicId() !== $token->getResourceOwner()->getPublicId()) {
            return 'Resource owner not authorized';
        }
    }
}

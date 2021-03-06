<?php

/*
 * The MIT License (MIT)
 *
 * Copyright (c) 2014-2015 Spomky-Labs
 *
 * This software may be modified and distributed under the terms
 * of the MIT license.  See the LICENSE file for details.
 */

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\SecurityPlugin\Annotation\Checker;

use SpomkyLabs\OAuth2ServerBundle\Plugin\SecurityPlugin\Annotation\OAuth2;
use SpomkyLabs\OAuth2ServerBundle\Plugin\SecurityPlugin\Security\Authentication\Token\OAuth2Token;

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

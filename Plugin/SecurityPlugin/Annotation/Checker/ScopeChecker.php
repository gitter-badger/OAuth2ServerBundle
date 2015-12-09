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

use OAuth2\Behaviour\HasScopeManager;
use SpomkyLabs\OAuth2ServerBundle\Plugin\SecurityPlugin\Annotation\OAuth2;
use SpomkyLabs\OAuth2ServerBundle\Plugin\SecurityPlugin\Security\Authentication\Token\OAuth2Token;

class ScopeChecker implements CheckerInterface
{
    use HasScopeManager;

    /**
     * {@inheritdoc}
     */
    public function check(OAuth2Token $token, OAuth2 $configuration)
    {
        if (null === $configuration->getScope()) {
            return;
        }

        // If the scope of the access token are not sufficient, then returns an authentication error
        $tokenScope = $this->getScopeManager()->convertToScope($token->getAccessToken()->getScope());
        $requiredScope = $this->getScopeManager()->convertToScope($configuration->getScope());
        if (!$this->getScopeManager()->checkScopes($requiredScope, $tokenScope)) {
            return 'Insufficient scope';
        }
    }
}

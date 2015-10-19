<?php

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\SecurityPlugin\Annotation\Checker;

use SpomkyLabs\OAuth2ServerBundle\Plugin\SecurityPlugin\Annotation\OAuth2;
use SpomkyLabs\OAuth2ServerBundle\Plugin\SecurityPlugin\Security\Authentication\Token\OAuth2Token;

interface CheckerInterface
{
    /**
     * @param \SpomkyLabs\OAuth2ServerBundle\Plugin\SecurityPlugin\Security\Authentication\Token\OAuth2Token $token
     * @param \SpomkyLabs\OAuth2ServerBundle\Plugin\SecurityPlugin\Annotation\OAuth2                         $configuration
     *
     * @return null|string
     */
    public function check(OAuth2Token $token, OAuth2 $configuration);
}

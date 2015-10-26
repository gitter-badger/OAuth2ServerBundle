<?php

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\JWTAccessTokenPlugin\Model;

interface JWTAccessTokenManagerInterface
{
    /**
     * @return int
     */
    public function deleteExpired();
}

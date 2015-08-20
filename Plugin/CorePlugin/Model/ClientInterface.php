<?php

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\CorePlugin\Model;

use OAuth2\Client\ClientInterface as BaseClientInterface;

interface ClientInterface extends ResourceOwnerInterface, BaseClientInterface
{
    /**
     * @param string[] $grant_types
     *
     * @return self
     */
    public function setAllowedGrantTypes(array $grant_types);

    /**
     * @return string[]
     */
    public function getAllowedGrantTypes();

    /**
     * @param string $grant_type
     *
     * @return self
     */
    public function addAllowedGrantType($grant_type);

    /**
     * @param string $grant_type
     *
     * @return self
     */
    public function removeAllowedGrantType($grant_type);
}

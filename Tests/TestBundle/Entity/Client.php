<?php

namespace SpomkyLabs\TestBundle\Entity;

use OAuth2\Client\ClientInterface;

abstract class Client extends ResourceOwner implements ClientInterface
{
    protected $public_id;
    protected $allowed_grant_types;

    public function getPublicId()
    {
        return $this->public_id;
    }

    public function setPublicId($public_id)
    {
        $this->public_id = $public_id;

        return $this;
    }

    public function setAllowedGrantTypes(array $grant_types)
    {
        $this->allowed_grant_types = $grant_types;

        return $this;
    }

    public function addAllowedGrantType($grant_type)
    {
        if (!$this->isAllowedGrantType($grant_type)) {
            $this->allowed_grant_types[] = $grant_type;
        }

        return $this;
    }

    public function removeAllowedGrantType($grant_type)
    {
        if ($this->isAllowedGrantType($grant_type)) {
            unset($this->allowed_grant_types[$grant_type]);
        }

        return $this;
    }

    public function isAllowedGrantType($grant_type)
    {
        return in_array($grant_type, $this->allowed_grant_types);
    }

    /**
     * {@inheritdoc}
     */
    public function getAllowedGrantTypes()
    {
        return $this->allowed_grant_types;
    }
}

<?php

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\CorePlugin\Model;

class Client extends ResourceOwner implements ClientInterface
{
    /**
     * @var string[]
     */
    protected $allowed_grant_types = array();

    /**
     * {@inheritdoc}
     */
    public function setAllowedGrantTypes(array $grant_types)
    {
        $this->allowed_grant_types = $grant_types;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function addAllowedGrantType($grant_type)
    {
        if (!$this->isAllowedGrantType($grant_type)) {
            $this->allowed_grant_types[] = $grant_type;
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function removeAllowedGrantType($grant_type)
    {
        if ($this->isAllowedGrantType($grant_type)) {
            unset($this->allowed_grant_types[$grant_type]);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
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

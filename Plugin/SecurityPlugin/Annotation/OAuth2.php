<?php

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\SecurityPlugin\Annotation;

use OAuth2\Client\ClientInterface;
use OAuth2\Client\ConfidentialClientInterface;
use OAuth2\Client\RegisteredClientInterface;
use OAuth2\EndUser\EndUserInterface;
use OAuth2\ResourceOwner\ResourceOwnerInterface;

/**
 * Annotation class for @OAuth2().
 *
 * @Annotation
 * @Target({"CLASS", "METHOD"})
 */
class OAuth2
{
    /**
     * @var null|string[]
     */
    private $scope = null;

    /**
     * @var null|string
     */
    private $client_type = null;

    /**
     * @var null|string
     */
    private $client_public_id = null;

    /**
     * @var null|string
     */
    private $resource_owner_type = null;

    /**
     * @var null|string
     */
    private $resource_owner_public_id = null;

    /**
     * @param array $data An array of key/value parameters.
     *
     * @throws \BadMethodCallException
     */
    public function __construct(array $data)
    {
        if (isset($data['value'])) {
            $data['path'] = $data['value'];
            unset($data['value']);
        }

        foreach ($data as $key => $value) {
            $method = 'set'.str_replace('_', '', $key);
            if (!method_exists($this, $method)) {
                throw new \BadMethodCallException(sprintf("Unknown property '%s' on annotation '%s'.", $key, get_class($this)));
            }
            $this->$method($value);
        }
    }

    /**
     * @param $client_type
     *
     * @return self
     */
    public function setClientType($client_type)
    {
        $this->client_type = $client_type;

        return $this;
    }

    /**
     * @return string
     */
    public function getClientType()
    {
        return $this->client_type;
    }

    /**
     * @param $client_public_id
     *
     * @return self
     */
    public function setClientPublicId($client_public_id)
    {
        $this->client_public_id = $client_public_id;

        return $this;
    }

    /**
     * @return string
     */
    public function getClientPublicId()
    {
        return $this->client_public_id;
    }

    /**
     * @param $resource_owner_type
     *
     * @return self
     */
    public function setResourceOwnerType($resource_owner_type)
    {
        $this->resource_owner_type = $resource_owner_type;

        return $this;
    }

    /**
     * @return string
     */
    public function getResourceOwnerType()
    {
        return $this->resource_owner_type;
    }

    /**
     * @param $resource_owner_public_id
     *
     * @return self
     */
    public function setResourceOwnerPublicId($resource_owner_public_id)
    {
        $this->resource_owner_public_id = $resource_owner_public_id;

        return $this;
    }

    /**
     * @return string
     */
    public function getResourceOwnerPublicId()
    {
        return $this->resource_owner_public_id;
    }

    /**
     * @param string|string[] $scope
     *
     * @return self
     */
    public function setScope($scope)
    {
        if (is_string($scope)) {
            $scope = explode(' ', $scope);
        }
        $this->scope = $scope;

        return $this;
    }

    /**
     * @return string[]
     */
    public function getScope()
    {
        return $this->scope;
    }
}

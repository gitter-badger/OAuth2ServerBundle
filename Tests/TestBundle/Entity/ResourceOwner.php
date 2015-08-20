<?php

namespace SpomkyLabs\TestBundle\Entity;

use OAuth2\ResourceOwner\ResourceOwnerInterface;

abstract class ResourceOwner implements ResourceOwnerInterface
{
    protected $id;
    protected $type;
    protected $public_id;

    public function __construct()
    {
        $this->public_id = hash('sha512', uniqid('', true));
    }

    public function getPublicId()
    {
        return $this->public_id;
    }

    public function setPublicId($public_id)
    {
        $this->public_id = $public_id;

        return $this;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }
}

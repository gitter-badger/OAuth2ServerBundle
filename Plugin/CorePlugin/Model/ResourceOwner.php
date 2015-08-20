<?php

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\CorePlugin\Model;

class ResourceOwner implements ResourceOwnerInterface
{
    /**
     * @var string
     */
    protected $public_id;

    /**
     * @var string
     */
    protected $type;

    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * {@inheritdoc}
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getPublicId()
    {
        return $this->public_id;
    }

    /**
     * {@inheritdoc}
     */
    public function setPublicId($public_id)
    {
        $this->public_id = $public_id;

        return $this;
    }
}

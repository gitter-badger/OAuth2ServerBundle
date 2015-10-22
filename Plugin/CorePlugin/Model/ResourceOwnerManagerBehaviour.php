<?php

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\CorePlugin\Model;

trait ResourceOwnerManagerBehaviour
{
    private $prefix;

    /**
     * @return string
     */
    protected function getPrefix()
    {
        return $this->prefix;
    }

    /**
     * @param string $prefix
     *
     * @return self
     */
    protected function setPrefix($prefix)
    {
        $this->prefix = $prefix;

        return $this;
    }

    /**
     * @param string $public_id
     *
     * @return bool
     */
    protected function isPublicIdSupported($public_id)
    {
        $prefix = $this->getPrefix();
        return empty($prefix) || $prefix === substr($public_id, 0, strlen($prefix));
    }
}

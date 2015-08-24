<?php

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\CorePlugin\Model;

trait ResourceOwnerManagerBehaviour
{
    /**
     * @return string
     */
    abstract protected function getPrefix();

    /**
     * @return string
     */
    abstract protected function getSuffix();

    /**
     * @param string $public_id
     *
     * @return bool
     */
    protected function isPublicIdSupported($public_id)
    {
        $prefix = $this->getPrefix();
        if (0 === strlen($prefix) && $prefix !== substr($public_id, 0, strlen($prefix))) {
            return false;
        }
        $suffix = $this->getSuffix();
        if (0 === strlen($suffix)) {
            return true;
        }

        return $suffix === substr($public_id, -strlen($suffix));
    }
}

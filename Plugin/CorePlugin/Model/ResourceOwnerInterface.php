<?php

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\CorePlugin\Model;

use OAuth2\ResourceOwner\ResourceOwnerInterface as Base;

interface ResourceOwnerInterface extends Base
{
    /**
     * @param string $public_id
     *
     * @return self
     */
    public function setPublicId($public_id);

    /**
     * @param string $type
     *
     * @return self
     */
    public function setType($type);
}

<?php

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\AuthCodeGrantTypePlugin\Model;

use OAuth2\Token\AuthCodeInterface as Base;

interface AuthCodeInterface extends Base
{
    /**
     * @param string $client_public_id
     *
     * @return self
     */
    public function setClientPublicId($client_public_id);

    /**
     * @param int $expires_at
     *
     * @return self
     */
    public function setExpiresAt($expires_at);

    /**
     * @param string[] $scope
     *
     * @return self
     */
    public function setScope($scope);

    /**
     * @param string $resource_owner_public_id
     *
     * @return self
     */
    public function setResourceOwnerPublicId($resource_owner_public_id);

    /**
     * @param string $code
     *
     * @return self
     */
    public function setCode($code);

    /**
     * @param bool $issue_refresh_token
     *
     * @return self
     */
    public function setIssueRefreshToken($issue_refresh_token);

    /**
     * @param string $redirect_uri
     *
     * @return self
     */
    public function setRedirectUri($redirect_uri);
}

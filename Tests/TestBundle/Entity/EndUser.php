<?php

namespace SpomkyLabs\TestBundle\Entity;

use OAuth2\EndUser\EndUserInterface;
use SpomkyLabs\OAuth2ServerBundle\Plugin\CorePlugin\Model\ResourceOwner;
use Symfony\Component\Security\Core\User\UserInterface;

class EndUser extends ResourceOwner implements EndUserInterface, UserInterface
{
    private $username;
    private $password;
    private $salt;
    private $roles;
    private $id;

    public function getId()
    {
        return $this->id;
    }

    public function getRoles()
    {
        return $this->roles;
    }

    public function setRoles(array $roles)
    {
        $this->roles = $roles;

        return $this;
    }

    public function getSalt()
    {
        return $this->salt;
    }

    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function eraseCredentials()
    {
    }

    public function equals(UserInterface $user)
    {
        if (!$user instanceof self) {
            return false;
        }

        if ($this->password !== $user->getPassword()) {
            return false;
        }

        if ($this->getSalt() !== $user->getSalt()) {
            return false;
        }

        if ($this->username !== $user->getUsername()) {
            return false;
        }

        return true;
    }
}

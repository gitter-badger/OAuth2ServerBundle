<?php

namespace SpomkyLabs\Bundle\CommonTestBundle\Service;

use SpomkyLabs\Bundle\CommonTestBundle\Entity\EndUser;
use SpomkyLabs\Bundle\CommonTestBundle\Entity\EndUserManager;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class UserProvider implements UserProviderInterface
{
    private $user_manager;

    public function __construct(EndUserManager $user_manager)
    {
        $this->user_manager = $user_manager;
    }

    public function loadUserByUsername($username)
    {
        $user = $this->user_manager->getEndUserByUsername($username);

        if ($user) {
            return $user;
        }

        throw new UsernameNotFoundException(sprintf('Username "%s" does not exist.', $username));
    }

    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof EndUser) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', get_class($user)));
        }

        return $this->loadUserByUsername($user->getUsername());
    }

    public function supportsClass($class)
    {
        return $class === 'SpomkyLabs\Bundle\CommonTestBundle\Entity\EndUser';
    }
}

<?php

namespace SpomkyLabs\TestBundle\Entity;

use OAuth2\Client\PasswordClientInterface as BasePasswordClientInterface;
use SpomkyLabs\OAuth2ServerBundle\Plugin\PasswordClientPlugin\Model\PasswordClientInterface;
use SpomkyLabs\OAuth2ServerBundle\Plugin\PasswordClientPlugin\Model\PasswordClientManager as BaseManager;

class PasswordClientManager extends BaseManager
{
    //This function generates the secret using a hash of the plaintext password and the salt
    public function updateClientCredentials(PasswordClientInterface $client)
    {
        if ($client->getPlainTextSecret() !== null) {
            $client->setSecret(hash('sha512', $client->getSalt().$client->getPlainTextSecret()));
            $client->eraseCredentials();
        }

        return $this;
    }

    //This function checks if the secret is valid
    protected function checkClientCredentials(BasePasswordClientInterface $client, $secret)
    {
        return $client->getSecret() === hash('sha512', $client->getSalt().$secret);
    }

    public function createClient()
    {
        $class = $this->getClass();

        return new $class();
    }

    public function saveClient(PasswordClientInterface $client)
    {
        $this->updateClientCredentials($client);
        $this->getEntityManager()->persist($client);
        $this->getEntityManager()->flush();
    }
}

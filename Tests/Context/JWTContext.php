<?php

namespace SpomkyLabs\OAuth2ServerBundle\Features\Context;

use SpomkyLabs\Jose\EncryptionInstruction;
use SpomkyLabs\Jose\SignatureInstruction;

trait JWTContext
{
    /**
     * Returns HttpKernel service container.
     *
     * @return \Symfony\Component\DependencyInjection\ContainerInterface
     */
    abstract public function getContainer();

    /**
     * @return \SpomkyLabs\OAuth2ServerBundle\Features\Context\RequestBuilder
     */
    abstract protected function getRequestBuilder();

    /**
     * @param string $key
     * @param string $value
     */
    abstract public function iAddKeyWithValueInTheBodyRequest($key, $value);

    /**
     * @Given I have a valid client assertion for client :client in the body request
     */
    public function IHaveAValidClientAssertionForClientInTheBodyRequest($client)
    {
        /*
         * @var \Jose\JWKManagerInterface
         */
        $key_manager = $this->getContainer()->get('jose.jwk_manager');
        $jwk1 = $key_manager->createJWK([
            'kid' => 'JWK1',
            'kty' => 'oct',
            'use' => 'enc',
            'k'   => 'ABEiM0RVZneImaq7zN3u_wABAgMEBQYHCAkKCwwNDg8',
        ]);
        $jwk2 = $key_manager->createJWK([
            'kid' => 'JWK2',
            'kty' => 'oct',
            'use' => 'sig',
            'k'   => 'AyM1SysPpbyDfgZld3umj1qzKObwVMkoqQ-EstJQLr_T-1qS0gZH75aKtMN3Yj0iPS4hcgUuTwjAzZr1Z9CAow',
        ]);

        $jose = $this->getContainer()->get('jose');

        $input = [
            'exp' => time() + 3600,
            'aud' => 'My Authorization Server',
            'iss' => 'My JWT issuer',
            'sub' => $client,
        ];

        $signature_instruction = new SignatureInstruction();
        $signature_instruction->setKey($jwk2)
            ->setProtectedHeader(['cty' => 'JWT','alg' => 'HS512'])
            ->setUnprotectedHeader([]);

        $encryption_instruction = new EncryptionInstruction();
        $encryption_instruction->setRecipientKey($jwk1);

        $jws = $jose->sign($input, [$signature_instruction]);
        $jwe = $jose->encrypt($jws, [$encryption_instruction], ['cty' => 'JWT', 'alg' => 'A256KW', 'enc' => 'A256CBC-HS512', 'exp' => time() + 3600, 'aud' => 'My Authorization Server', 'iss' => 'My JWT issuer', 'sub' => $client]);

        $this->iAddKeyWithValueInTheBodyRequest('client_assertion_type', 'urn:ietf:params:oauth:client-assertion-type:jwt-bearer');
        $this->iAddKeyWithValueInTheBodyRequest('client_assertion', $jwe);
    }
}

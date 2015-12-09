<?php

/*
 * The MIT License (MIT)
 *
 * Copyright (c) 2014-2015 Spomky-Labs
 *
 * This software may be modified and distributed under the terms
 * of the MIT license.  See the LICENSE file for details.
 */

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\SecurityPlugin\Security\EntryPoint;

use OAuth2\Behaviour\HasAccessTokenTypeManager;
use OAuth2\Behaviour\HasExceptionManager;
use OAuth2\Exception\ExceptionManagerInterface;
use Symfony\Bridge\PsrHttpMessage\Factory\HttpFoundationFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;
use Zend\Diactoros\Response;

class OAuth2EntryPoint implements AuthenticationEntryPointInterface
{
    use HasExceptionManager;
    use HasAccessTokenTypeManager;

    /**
     * @param \Symfony\Component\HttpFoundation\Request                               $request
     * @param \Symfony\Component\Security\Core\Exception\AuthenticationException|null $authException
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function start(Request $request, AuthenticationException $authException = null)
    {
        $schemes = ['schemes' => []];
        foreach ($this->getAccessTokenTypeManager()->getAccessTokenTypes() as $type) {
            $params = $type->getSchemeParameters();
            if (!empty($params)) {
                $schemes['schemes'] = array_merge($schemes['schemes'], $params);
            }
        }

        $exception = $this->getExceptionManager()->getException(
            ExceptionManagerInterface::AUTHENTICATE,
            ExceptionManagerInterface::ACCESS_DENIED,
            'OAuth2 authentication required',
            $schemes
        );

        $response = new Response();

        $exception->getHttpResponse($response);

        $factory = new HttpFoundationFactory();
        $response = $factory->createResponse($response);

        return $response;
    }
}

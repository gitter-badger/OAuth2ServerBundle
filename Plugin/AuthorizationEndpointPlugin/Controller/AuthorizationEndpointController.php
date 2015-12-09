<?php

/*
 * The MIT License (MIT)
 *
 * Copyright (c) 2014-2015 Spomky-Labs
 *
 * This software may be modified and distributed under the terms
 * of the MIT license.  See the LICENSE file for details.
 */

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\AuthorizationEndpointPlugin\Controller;

use OAuth2\Client\ClientInterface;
use OAuth2\Client\ClientManagerSupervisorInterface;
use OAuth2\Endpoint\Authorization;
use OAuth2\Endpoint\AuthorizationFactory;
use OAuth2\EndUser\EndUserInterface;
use OAuth2\Exception\BaseExceptionInterface;
use OAuth2\Scope\ScopeManagerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SpomkyLabs\OAuth2ServerBundle\Plugin\AuthorizationEndpointPlugin\Form\Handler\AuthorizationFormHandler;
use SpomkyLabs\OAuth2ServerBundle\Plugin\CorePlugin\Event\Events;
use SpomkyLabs\OAuth2ServerBundle\Plugin\CorePlugin\Event\PostAuthorizationEvent;
use SpomkyLabs\OAuth2ServerBundle\Plugin\CorePlugin\Event\PreAuthorizationEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Templating\EngineInterface;
use Zend\Diactoros\Response;
use Zend\Diactoros\Stream;

class AuthorizationEndpointController
{
    /**
     * @var \Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface
     */
    protected $token_storage;

    /**
     * @var \Symfony\Component\Templating\EngineInterface
     */
    protected $template_engine;

    /**
     * @var \SpomkyLabs\OAuth2ServerBundle\Plugin\AuthorizationEndpointPlugin\Form\Handler\AuthorizationFormHandler
     */
    protected $form_handler;

    /**
     * @var \OAuth2\Client\ClientManagerSupervisorInterface
     */
    protected $client_manager_supervisor;

    /**
     * @var \Symfony\Component\Form\FormInterface
     */
    protected $form;

    /**
     * @var \OAuth2\Scope\ScopeManagerInterface
     */
    protected $scope_manager;

    /**
     * @var \OAuth2\Endpoint\AuthorizationFactory
     */
    protected $authorization_factory;

    /**
     * @var string|null
     */
    protected $x_frame_options;

    /**
     * @var \Symfony\Component\EventDispatcher\EventDispatcherInterface|null
     */
    protected $event_dispatcher;

    public function __construct(
        TokenStorageInterface $token_storage,
        EngineInterface $template_engine,
        AuthorizationFormHandler $form_handler,
        ClientManagerSupervisorInterface $client_manager_supervisor,
        FormInterface $form,
        ScopeManagerInterface $scope_manager,
        AuthorizationFactory $authorization_factory,
        $x_frame_options,
        EventDispatcherInterface $event_dispatcher = null
    ) {
        $this->x_frame_options = $x_frame_options;
        $this->token_storage = $token_storage;
        $this->template_engine = $template_engine;
        $this->form_handler = $form_handler;
        $this->client_manager_supervisor = $client_manager_supervisor;
        $this->form = $form;
        $this->authorization_factory = $authorization_factory;
        $this->scope_manager = $scope_manager;
        $this->event_dispatcher = $event_dispatcher;
    }

    public function authorizationAction(ServerRequestInterface $request)
    {
        $response = new Response();
        try {
            $authorization = $this->createAuthorization($request);
            if (null !== $this->event_dispatcher) {
                $this->event_dispatcher->dispatch(Events::OAUTH2_PRE_AUTHORIZATION, new PreAuthorizationEvent($authorization));
            }
            $this->form->setData($authorization);

            if ('POST' === $request->getMethod()) {
                $this->form_handler->handle($this->form, $request, $response, $authorization);

                if (null !== $this->event_dispatcher) {
                    $this->event_dispatcher->dispatch(Events::OAUTH2_POST_AUTHORIZATION, new PostAuthorizationEvent($authorization));
                }

                return $response;
            }
        } catch (BaseExceptionInterface $e) {
            $e->getHttpResponse($response);

            return $response;
        }

        $this->prepareResponse($authorization, $response);

        return $response;
    }

    /**
     * @param \OAuth2\Endpoint\Authorization      $authorization
     * @param \Psr\Http\Message\ResponseInterface $response
     */
    private function prepareResponse(Authorization $authorization, ResponseInterface &$response)
    {
        $content = $this->template_engine->render(
            '/spomky-labs/oauth2-server/authorization/template/Authorization/authorization.html.twig',
            [
                'form'          => $this->form->createView(),
                'authorization' => $authorization,
            ]
        );
        $body = new Stream('php://temp', 'wb+');
        $body->write($content);
        $response = new Response($body);
        if (null !== $this->x_frame_options) {
            $response = $response->withHeader('X-Frame-Options', $this->x_frame_options);
        }
    }

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     *
     * @return \OAuth2\Endpoint\Authorization
     */
    private function createAuthorization(ServerRequestInterface $request)
    {
        $user = $this->token_storage->getToken()->getUser();
        if (!$user instanceof EndUserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        $authorization = $this->authorization_factory->createFromRequest($request);

        if (!$authorization instanceof Authorization) {
            throw new BadRequestHttpException('Unable to create the authorization from the request.');
        }

        if (!$authorization->getClient() instanceof ClientInterface) {
            throw new BadRequestHttpException('"client_id" parameter is missing or client is unknown.');
        }

        $authorization->setEndUser($user);

        return $authorization;
    }
}

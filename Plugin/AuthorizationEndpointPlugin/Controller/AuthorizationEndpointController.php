<?php

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\AuthorizationEndpointPlugin\Controller;

use OAuth2\Client\ClientInterface;
use OAuth2\Client\ClientManagerSupervisorInterface;
use OAuth2\Endpoint\Authorization;
use OAuth2\Endpoint\AuthorizationInterface;
use OAuth2\EndUser\EndUserInterface;
use OAuth2\Exception\BaseExceptionInterface;
use OAuth2\Scope\ScopeManagerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SpomkyLabs\OAuth2ServerBundle\Plugin\AuthorizationEndpointPlugin\Form\Handler\AuthorizationFormHandler;
use Symfony\Component\Form\FormInterface;
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
     * @var string|null
     */
    protected $x_frame_options;

    public function __construct(
        TokenStorageInterface $token_storage,
        EngineInterface $template_engine,
        AuthorizationFormHandler $form_handler,
        ClientManagerSupervisorInterface $client_manager_supervisor,
        FormInterface $form,
        ScopeManagerInterface $scope_manager,
        $x_frame_options
    ) {
        $this->x_frame_options = $x_frame_options;
        $this->token_storage = $token_storage;
        $this->template_engine = $template_engine;
        $this->form_handler = $form_handler;
        $this->client_manager_supervisor = $client_manager_supervisor;
        $this->form = $form;
        $this->scope_manager = $scope_manager;
    }

    public function authorizationAction(ServerRequestInterface $request)
    {
        $user = $this->token_storage->getToken()->getUser();
        if (!$user instanceof EndUserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        $response = new Response();
        try {
            $authorization = $this->createAuthorization($request);
            $authorization->setResourceOwner($user);
            $this->form->setData($authorization);

            if ('POST' === $request->getMethod()) {
                $this->form_handler->handle($this->form, $request, $response, $authorization);

                return $response;
            }
        } catch (BaseExceptionInterface $e) {
            $e->getHttpResponse($response);

            return $response;
        }

        $this->prepareResponse($authorization, $response);

        return $response;
    }

    private function prepareResponse(AuthorizationInterface $authorization, ResponseInterface &$response)
    {
        $content = $this->template_engine->render(
            '/spomky-labs/oauth2-server/authorization/template/Authorization/authorization.html.twig',
            [
                'form' => $this->form->createView(),
                'client' => $authorization->getClient(),
                'scopes' => $authorization->getScope(),
            ]
        );
        $body = new Stream('php://temp', 'wb+');
        $body->write($content);
        $response = new Response($body);
        if (!is_null($this->x_frame_options)) {
            $response = $response->withHeader('X-Frame-Options', $this->x_frame_options);
        }
    }

    private function createAuthorization(ServerRequestInterface $request)
    {
        $authorization = new Authorization();

        $params = $request->getQueryParams();
        $client_id = array_key_exists('client_id', $params) ? $params['client_id'] : null;
        $this->checkClientId($client_id);

        $client = $this->client_manager_supervisor->getClient($client_id);
        $this->checkClient($client);

        $response_type = array_key_exists('response_type', $params) ? $params['response_type'] : null;
        $this->checkResponseType($response_type);

        $scope = $this->scope_manager->convertToScope(array_key_exists('scope', $params) ? $params['scope'] : null);
        $authorization->setClient($client)
                      ->setResponseType(array_key_exists('response_type', $params) ? $params['response_type'] : null)
                      ->setScope($scope)
                      ->setRedirectUri(array_key_exists('redirect_uri', $params) ? $params['redirect_uri'] : null)
                      ->setIssueRefreshToken(array_key_exists('issue_refresh_token', $params) ? $params['issue_refresh_token'] : null)
                      ->setState(array_key_exists('state', $params) ? $params['state'] : null);

        return $authorization;
    }

    private function checkClientId($client_id)
    {
        if (is_null($client_id)) {
            throw new \Exception('Client ID not valid or missing');
        }
    }

    private function checkClient($client)
    {
        if (!$client instanceof ClientInterface) {
            throw new \Exception('Client ID not valid or missing');
        }
    }

    private function checkResponseType($response_type)
    {
        if (is_null($response_type)) {
            throw new \Exception("'response_type' parameter not valid or missing");
        }
    }
}

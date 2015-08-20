<?php

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\AuthorizationEndpointPlugin\Controller;

use OAuth2\Client\ClientInterface;
use OAuth2\Client\ClientManagerSupervisorInterface;
use OAuth2\Endpoint\Authorization;
use OAuth2\Endpoint\AuthorizationInterface;
use OAuth2\EndUser\EndUserInterface;
use OAuth2\Exception\BaseExceptionInterface;
use OAuth2\Scope\ScopeManagerInterface;
use SpomkyLabs\OAuth2ServerBundle\Plugin\AuthorizationEndpointPlugin\Form\Handler\AuthorizationFormHandler;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Templating\EngineInterface;

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
     * @var
     */
    protected $resource_owner_manager;

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

    public function authorizationAction(Request $request)
    {
        $user = $this->token_storage->getToken()->getUser();
        if (!$user instanceof EndUserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        try {
            $authorization = $this->createAuthorization($request);
            $authorization->setResourceOwner($user);
            $this->form->setData($authorization);

            if ($request->isMethod(Request::METHOD_POST)) {
                return $this->form_handler->handle($this->form, $request, $authorization);
            }
        } catch (BaseExceptionInterface $e) {
            return $e->getHttpResponse();
        }

        return $this->prepareResponse($authorization);
    }

    private function prepareResponse(AuthorizationInterface $authorization)
    {
        $content = $this->template_engine->render(
            '/spomky-labs/oauth2-server/authorization/template/Authorization/authorization.html.twig',
            [
                'form'   => $this->form->createView(),
                'client' => $authorization->getClient(),
                'scopes' => $authorization->getScope(),
            ]
        );
        $response = new Response($content);
        if (!is_null($this->x_frame_options)) {
            $response->headers->set('X-Frame-Options', $this->x_frame_options);
        }

        return $response;
    }

    private function createAuthorization(Request $request)
    {
        $authorization = new Authorization();

        $client_id = $request->query->get('client_id');
        $this->checkClientId($client_id);

        $client = $this->client_manager_supervisor->getClient($client_id);
        $this->checkClient($client);

        $response_type = $request->query->get('response_type');
        $this->checkResponseType($response_type);

        $scope = $this->scope_manager->convertToScope($request->query->get('scope'));
        $authorization->setClient($client)
                      ->setResponseType($request->query->get('response_type'))
                      ->setScope($scope)
                      ->setRedirectUri($request->query->get('redirect_uri'))
                      ->setIssueRefreshToken($request->query->get('issue_refresh_token'))
                      ->setState($request->query->get('state'));

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

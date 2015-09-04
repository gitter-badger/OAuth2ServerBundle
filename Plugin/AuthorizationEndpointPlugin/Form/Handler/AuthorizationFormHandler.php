<?php

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\AuthorizationEndpointPlugin\Form\Handler;

use OAuth2\Endpoint\Authorization;
use OAuth2\Endpoint\AuthorizationEndpointInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Bridge\PsrHttpMessage\Factory\HttpFoundationFactory;
use Symfony\Component\Form\ClickableInterface;
use Symfony\Component\Form\Exception\InvalidArgumentException;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class AuthorizationFormHandler
{
    protected $endpoint;

    public function __construct(AuthorizationEndpointInterface $endpoint)
    {
        $this->endpoint = $endpoint;
    }

    public function handle(FormInterface $form, ServerRequestInterface $request, ResponseInterface &$response, Authorization $authorization)
    {
        if ('POST' !== $request->getMethod()) {
            return false;
        }

        $httpFoundationFactory = new HttpFoundationFactory();
        $symfony_request = $httpFoundationFactory->createRequest($request);

        $form->submit($symfony_request);
        if (!$form->isValid()) {
            return false;
        }

        $button = $form->get('accept');
        if (!$button instanceof ClickableInterface) {
            throw new InvalidArgumentException('Unable to find the button named "accept".');
        }
        $authorization->setAuthorized($button->isClicked());

        $this->endpoint->authorize($authorization, $response);
    }
}

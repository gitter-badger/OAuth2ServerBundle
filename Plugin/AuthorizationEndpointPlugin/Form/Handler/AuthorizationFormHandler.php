<?php

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\AuthorizationEndpointPlugin\Form\Handler;

use OAuth2\Endpoint\Authorization;
use OAuth2\Endpoint\AuthorizationEndpointInterface;
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

    public function handle(FormInterface $form, Request $request, Authorization $authorization)
    {
        if (!$request->isMethod(Request::METHOD_POST)) {
            return false;
        }

        $form->submit($request);
        if (!$form->isValid()) {
            return false;
        }

        $button = $form->get('accept');
        if (!$button instanceof ClickableInterface) {
            throw new InvalidArgumentException('Unable to find the button named "accept".');
        }
        $authorization->setAuthorized($button->isClicked());

        return $this->endpoint->authorize($authorization);
    }
}

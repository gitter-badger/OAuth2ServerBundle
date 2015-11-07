<?php

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\PublicClientPlugin\Form\Handler;

use SpomkyLabs\OAuth2ServerBundle\Plugin\PublicClientPlugin\Model\PublicClientManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class PublicClientFormHandler
{
    protected $manager;

    public function __construct(PublicClientManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    public function handle(FormInterface $form, Request $request)
    {
        if ('POST' !== $request->getMethod()) {
            return false;
        }

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->manager->saveClient($form->getData());
            return true;
        }
        return false;
    }
}

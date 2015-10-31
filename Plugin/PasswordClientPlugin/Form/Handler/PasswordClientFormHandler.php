<?php

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\PasswordClientPlugin\Form\Handler;

use SpomkyLabs\OAuth2ServerBundle\Plugin\PasswordClientPlugin\Model\PasswordClientManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class PasswordClientFormHandler
{
    protected $manager;

    public function __construct(PasswordClientManagerInterface $manager)
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
            return false;
        }
        var_dump($form->getData());
        $this->manager->saveClient($form->getData());
        return true;
    }
}

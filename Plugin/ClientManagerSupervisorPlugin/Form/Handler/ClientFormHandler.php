<?php

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\ClientManagerSupervisorPlugin\Form\Handler;

use SpomkyLabs\OAuth2ServerBundle\Plugin\ClientManagerSupervisorPlugin\Model\ClientManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class ClientFormHandler
{
    /**
     * @var \SpomkyLabs\OAuth2ServerBundle\Plugin\ClientManagerSupervisorPlugin\Model\ClientManagerInterface
     */
    protected $client_manager;

    /**
     * RegisteredClientFormHandler constructor.
     *
     * @param \SpomkyLabs\OAuth2ServerBundle\Plugin\ClientManagerSupervisorPlugin\Model\ClientManagerInterface $client_manager
     */
    public function __construct(ClientManagerInterface $client_manager)
    {
        $this->client_manager = $client_manager;
    }

    /**
     * @param \Symfony\Component\Form\FormInterface     $form
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return bool
     */
    public function handle(FormInterface $form, Request $request)
    {
        if ('POST' !== $request->getMethod()) {
            return false;
        }

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->client_manager->saveClient($form->getData());
            return true;
        }
        return false;
    }

    /**
     * @return \SpomkyLabs\OAuth2ServerBundle\Plugin\ClientManagerSupervisorPlugin\Model\ClientManagerInterface
     */
    protected function getClientManager()
    {
        return $this->client_manager;
    }
}

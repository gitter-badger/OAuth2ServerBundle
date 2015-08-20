<?php

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\ClientManagerSupervisorPlugin\Event;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\Request;

class PreFindClientEvent extends Event
{
    /**
     * @var \Symfony\Component\HttpFoundation\Request
     */
    protected $request;

    /**
     * @var bool
     */
    protected $throw_exception_if_not_found;

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param bool                                      $throw_exception_if_not_found
     */
    public function __construct(Request $request, $throw_exception_if_not_found = true)
    {
        $this->request = $request;
        $this->throw_exception_if_not_found = $throw_exception_if_not_found;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Request
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @return bool
     */
    public function getThrowExceptionIfNotFound()
    {
        return $this->throw_exception_if_not_found;
    }
}

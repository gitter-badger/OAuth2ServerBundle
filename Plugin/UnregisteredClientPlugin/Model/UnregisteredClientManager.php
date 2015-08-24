<?php

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\UnregisteredClientPlugin\Model;

use OAuth2\Exception\ExceptionManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class UnregisteredClientManager implements UnregisteredClientManagerInterface
{
    /**
     * @var \OAuth2\Exception\ExceptionManagerInterface
     */
    private $exception_manager;

    /**
     * @var string
     */
    private $class;

    /**
     * @param string                                      $class
     * @param \OAuth2\Exception\ExceptionManagerInterface $exception_manager
     */
    public function __construct(
        $class,
        ExceptionManagerInterface $exception_manager
    ) {
        $this->class = $class;
        $this->exception_manager = $exception_manager;
    }

    /**
     * {@inheritdoc}
     */
    protected function getExceptionManager()
    {
        return $this->exception_manager;
    }

    /**
     * @return string
     */
    protected function getClass()
    {
        return $this->class;
    }

    /**
     * {@inheritdoc}
     */
    public function getClient($public_id)
    {
        if (!$this->isPublicIdSupported($public_id)) {
            return;
        }
        $class = $this->getClass();
        /*
         * @var $client \SpomkyLabs\OAuth2ServerBundle\Plugin\UnregisteredClientPlugin\Model\UnregisteredClientInterface
         */
        $client = new $class();
        $client->setPublicId($public_id);

        return $client;
    }

    /**
     * {@inheritdoc}
     */
    protected function findClientMethods()
    {
        return [
            'findClientInRequestHeader',
        ];
    }

    /**
     * Public client are identified with header name 'X-OAuth2-Unregistered-Client-ID' in the request.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return null|string
     */
    protected function findClientInRequestHeader(Request $request)
    {
        $header = $request->headers->get('X-OAuth2-Unregistered-Client-ID');

        return empty($header) ? null : $header;
    }

    /**
     * {@inheritdoc}
     */
    public function findClient(Request $request)
    {
        $methods = $this->findClientMethods();
        $result = [];

        foreach ($methods as $method) {
            $data = $this->$method($request);
            if (null !== $data) {
                $result[] = $data;
            }
        }

        $client = $this->checkResult($result);
        if (is_null($client)) {
            return $client;
        }

        if (!$client instanceof UnregisteredClientInterface) {
            throw $this->getExceptionManager()->getException(ExceptionManagerInterface::INTERNAL_SERVER_ERROR, ExceptionManagerInterface::INVALID_CLIENT, 'The client is not an instance of UnregisteredClientInterface.');
        }

        return $client;
    }

    /**
     * @param array $result
     *
     * @throws \OAuth2\Exception\BaseExceptionInterface
     *
     * @return null|\OAuth2\Client\ClientInterface
     */
    private function checkResult(array $result)
    {
        if (count($result) > 1) {
            throw $this->getExceptionManager()->getException(ExceptionManagerInterface::BAD_REQUEST, ExceptionManagerInterface::INVALID_REQUEST, 'Only one authentication method may be used to authenticate the client.');
        }

        if (count($result) < 1) {
            return;
        }

        $client = $this->getClient($result[0]);

        return $client;
    }

    /**
     * @return string
     */
    protected function getPrefix()
    {
        return '**UNREGISTERED**_';
    }

    /**
     * @return string
     */
    protected function getSuffix()
    {
        return '_**UNREGISTERED**';
    }
}

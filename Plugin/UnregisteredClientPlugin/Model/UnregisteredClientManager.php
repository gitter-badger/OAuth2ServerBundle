<?php

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\UnregisteredClientPlugin\Model;

use OAuth2\Exception\ExceptionManagerInterface;
use Psr\Http\Message\ServerRequestInterface;
use SpomkyLabs\OAuth2ServerBundle\Plugin\CorePlugin\Model\ResourceOwnerManagerBehaviour;

class UnregisteredClientManager implements UnregisteredClientManagerInterface
{
    use ResourceOwnerManagerBehaviour;

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
     * @param string                                      $prefix
     * @param \OAuth2\Exception\ExceptionManagerInterface $exception_manager
     */
    public function __construct($class, $prefix, ExceptionManagerInterface $exception_manager)
    {
        $this->class = $class;
        $this->setPrefix($prefix);
        $this->exception_manager = $exception_manager;
    }

    public function getSchemesParameters()
    {
        return [];
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
         * @var \SpomkyLabs\OAuth2ServerBundle\Plugin\UnregisteredClientPlugin\Model\UnregisteredClientInterface
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
     */

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     *
     * @return string|null
     */
    protected function findClientInRequestHeader(ServerRequestInterface $request)
    {
        $header = $request->getHeader('X-OAuth2-Unregistered-Client-ID');

        return count($header) === 0 ? null : $header[0];
    }

    /**
     * {@inheritdoc}
     */
    public function findClient(ServerRequestInterface $request, &$client_public_id_found = null)
    {
        $methods = $this->findClientMethods();
        $result = [];

        foreach ($methods as $method) {
            $data = $this->$method($request, $client_public_id_found);
            if (null !== $data) {
                $result[] = $data;
            }
        }

        $client = $this->checkResult($result);
        if (null === $client) {
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
}

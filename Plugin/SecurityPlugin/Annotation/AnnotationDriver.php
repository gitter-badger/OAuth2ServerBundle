<?php

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\SecurityPlugin\Annotation;

use Doctrine\Common\Annotations\Reader;
use OAuth2\Behaviour\HasAccessTokenManager;
use OAuth2\Behaviour\HasAccessTokenTypeManager;
use OAuth2\Behaviour\HasExceptionManager;
use OAuth2\Behaviour\HasScopeManager;
use OAuth2\Client\ClientInterface;
use OAuth2\Client\ConfidentialClientInterface;
use OAuth2\Client\RegisteredClientInterface;
use OAuth2\EndUser\EndUserInterface;
use OAuth2\Exception\ExceptionManagerInterface;
use SpomkyLabs\OAuth2ServerBundle\Plugin\SecurityPlugin\Security\Authentication\Token\OAuth2Token;
use Symfony\Bridge\PsrHttpMessage\Factory\HttpFoundationFactory;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Zend\Diactoros\Response;

class AnnotationDriver
{
    use HasScopeManager;
    use HasAccessTokenTypeManager;
    use HasAccessTokenManager;
    use HasExceptionManager;
    /**
     * @var \Doctrine\Common\Annotations\Reader
     */
    private $reader;

    /**
     * @var \Symfony\Component\Security\Core\SecurityContextInterface
     */
    private $security_context;

    /**
     * @param \Doctrine\Common\Annotations\Reader                       $reader
     * @param \Symfony\Component\Security\Core\SecurityContextInterface $security_context
     */
    public function __construct(
        Reader $reader,
        SecurityContextInterface $security_context
    ) {
        $this->reader = $reader;
        $this->security_context = $security_context;
    }

    public function onKernelController(FilterControllerEvent $event)
    {
        if (!is_array($controller = $event->getController())) {
            return;
        }

        $object = new \ReflectionObject($controller[0]);
        $method = $object->getMethod($controller[1]);
        $classConfigurations = $this->reader->getClassAnnotations($object);
        $methodConfigurations = $this->reader->getMethodAnnotations($method);

        foreach (array_merge($classConfigurations, $methodConfigurations) as $configuration) {
            if ($configuration instanceof OAuth2) {
                $token = $this->security_context->getToken();
                $exception = null;

                // If no access token is found by the firewall, then returns an authentication error
                if (!$token instanceof OAuth2Token) {
                    $this->createAuthenticationException($event, 'OAuth2 authentication required', $configuration->getScope());

                    return;
                }

                foreach(['checkScope', 'checkClientType', 'checkResourceOwnerType', 'checkClientPublicId', 'checkResourceOwnerPublicId'] as $method) {
                    $result = $this->$method($event, $token, $configuration);
                    if (false === $result) {
                        return;
                    }
                }
            }
        }
    }

    /**
     * @param \Symfony\Component\HttpKernel\Event\FilterControllerEvent $event
     * @param string                                                    $message
     * @param null|string[]                                             $scope
     */
    private function createAuthenticationException(FilterControllerEvent &$event, $message, $scope)
    {
        $params = [
            'scheme' => $this->getAccessTokenTypeManager()->getDefaultAccessTokenType()->getScheme(),
        ];
        if (null !== $scope) {
            $params['scope'] = implode(' ', $scope);
        }

        $exception = $this->getExceptionManager()->getException(ExceptionManagerInterface::AUTHENTICATE, ExceptionManagerInterface::ACCESS_DENIED, $message, $params);

        $event->setController(function () use ($exception) {
            $response = new Response();
            $exception->getHttpResponse($response);
            $response->getBody()->rewind();

            $factory = new HttpFoundationFactory();
            $response = $factory->createResponse($response);
            return $response;
        });
    }

    /**
     * @param \Symfony\Component\HttpKernel\Event\FilterControllerEvent                                      $event
     * @param \SpomkyLabs\OAuth2ServerBundle\Plugin\SecurityPlugin\Security\Authentication\Token\OAuth2Token $token
     * @param \SpomkyLabs\OAuth2ServerBundle\Plugin\SecurityPlugin\Annotation\OAuth2                         $configuration
     *
     * @return bool
     */
    private function checkScope(FilterControllerEvent &$event, OAuth2Token $token, OAuth2 $configuration)
    {
        if (null === $configuration->getScope()) {
            return true;
        }

        // If the scope of the access token are not sufficient, then returns an authentication error
        $tokenScope = $this->getScopeManager()->convertToScope($token->getAccessToken()->getScope());
        $requiredScope = $this->getScopeManager()->convertToScope($configuration->getScope());
        if (!$this->getScopeManager()->checkScopes($requiredScope, $tokenScope)) {
            $this->createAuthenticationException($event, 'Insufficient scope', $configuration->getScope());
            return false;
        }
        return true;
    }

    /**
     * @param \Symfony\Component\HttpKernel\Event\FilterControllerEvent                                      $event
     * @param \SpomkyLabs\OAuth2ServerBundle\Plugin\SecurityPlugin\Security\Authentication\Token\OAuth2Token $token
     * @param \SpomkyLabs\OAuth2ServerBundle\Plugin\SecurityPlugin\Annotation\OAuth2                         $configuration
     *
     * @return bool
     */
    private function checkResourceOwnerType(FilterControllerEvent &$event, OAuth2Token $token, OAuth2 $configuration)
    {
        if (null === $configuration->getResourceOwnerType()) {
            return true;
        }

        $result = $this->isTypeValid( $configuration->getResourceOwnerType(), $token->getResourceOwner());
        if (false === $result) {
            $this->createAuthenticationException($event, 'Bad resource owner type', $configuration->getScope());

            return false;
        }

        return true;
    }

    /**
     * @param \Symfony\Component\HttpKernel\Event\FilterControllerEvent                                      $event
     * @param \SpomkyLabs\OAuth2ServerBundle\Plugin\SecurityPlugin\Security\Authentication\Token\OAuth2Token $token
     * @param \SpomkyLabs\OAuth2ServerBundle\Plugin\SecurityPlugin\Annotation\OAuth2                         $configuration
     *
     * @return bool
     */
    private function checkClientPublicId(FilterControllerEvent &$event, OAuth2Token $token, OAuth2 $configuration)
    {
        if (null === $configuration->getClientPublicId()) {
            return true;
        }

        if ($configuration->getClientPublicId() !== $token->getClient()->getPublicId()) {
            $this->createAuthenticationException($event, 'Client not authorized', $configuration->getScope());

            return false;
        }

        return true;
    }

    /**
     * @param \Symfony\Component\HttpKernel\Event\FilterControllerEvent                                      $event
     * @param \SpomkyLabs\OAuth2ServerBundle\Plugin\SecurityPlugin\Security\Authentication\Token\OAuth2Token $token
     * @param \SpomkyLabs\OAuth2ServerBundle\Plugin\SecurityPlugin\Annotation\OAuth2                         $configuration
     *
     * @return bool
     */
    private function checkResourceOwnerPublicId(FilterControllerEvent &$event, OAuth2Token $token, OAuth2 $configuration)
    {
        if (null === $configuration->getResourceOwnerPublicId()) {
            return true;
        }

        if ($configuration->getResourceOwnerPublicId() !== $token->getResourceOwner()->getPublicId()) {
            $this->createAuthenticationException($event, 'Resource owner not authorized', $configuration->getScope());

            return false;
        }

        return true;
    }

    /**
     * @param \Symfony\Component\HttpKernel\Event\FilterControllerEvent                                      $event
     * @param \SpomkyLabs\OAuth2ServerBundle\Plugin\SecurityPlugin\Security\Authentication\Token\OAuth2Token $token
     * @param \SpomkyLabs\OAuth2ServerBundle\Plugin\SecurityPlugin\Annotation\OAuth2                         $configuration
     *
     * @return bool
     */
    private function checkClientType(FilterControllerEvent &$event, OAuth2Token $token, OAuth2 $configuration)
    {
        if (null === $configuration->getClientType()) {
            return true;
        }

        $result = $this->isTypeValid( $configuration->getClientType(), $token->getClient());
        if (false === $result) {
            $this->createAuthenticationException($event, 'Bad client type', $configuration->getScope());

            return false;
        }

        return true;
    }

    /**
     * @param string                         $type
     * @param \OAuth2\Client\ClientInterface $client
     *
     * @return bool
     */
    private function isTypeValid($type, ClientInterface $client)
    {
        switch ($type) {
            case 'end_user':
                return $client instanceof EndUserInterface;
            case 'client':
                return $client instanceof ClientInterface;
            case 'registered_client':
                return $client instanceof RegisteredClientInterface;
            case 'confidential_client':
                return $client instanceof ConfidentialClientInterface;
            case 'public_client':
                return $client instanceof RegisteredClientInterface && !$client instanceof ConfidentialClientInterface;
            case 'unregistered_client':
                return $client instanceof ClientInterface && !$client instanceof RegisteredClientInterface;
            default:
                return $type === $client->getType();
        }
    }
}

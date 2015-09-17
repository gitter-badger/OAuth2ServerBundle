<?php

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\SecurityPlugin\Annotation;

use Doctrine\Common\Annotations\Reader;
use OAuth2\Token\AccessTokenTypeInterface;
use OAuth2\Token\AccessTokenTypeManager;
use OAuth2\Token\AccessTokenTypeManagerInterface;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use OAuth2\Scope\ScopeManagerInterface;
use OAuth2\Token\AccessTokenInterface;
use OAuth2\Token\AccessTokenManagerInterface;
use OAuth2\Exception\ExceptionManagerInterface;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Zend\Diactoros\Response;

class AnnotationDriver
{
    /**
     * @var \Doctrine\Common\Annotations\Reader
     */
    private $reader;

    /**
     * @var \OAuth2\Scope\ScopeManagerInterface
     */
    private $scope_manager;

    /**
     * @var \OAuth2\Token\AccessTokenTypeManagerInterface
     */
    private $access_token_type_manager;

    /**
     * @var \OAuth2\Token\AccessTokenManagerInterface
     */
    private $access_token_manager;

    /**
     * @var \OAuth2\Exception\ExceptionManagerInterface
     */
    private $exception_manager;

    /**
     * @var \Symfony\Component\Security\Core\SecurityContextInterface
     */
    private $security_context;

    /**
     * @param \Doctrine\Common\Annotations\Reader                       $reader
     * @param \OAuth2\Scope\ScopeManagerInterface                       $scope_manager
     * @param \OAuth2\Token\AccessTokenTypeManagerInterface             $access_token_type_manager
     * @param \OAuth2\Token\AccessTokenManagerInterface                 $access_token_manager
     * @param \OAuth2\Exception\ExceptionManagerInterface               $exception_manager
     * @param \Symfony\Component\Security\Core\SecurityContextInterface $security_context
     */
    public function __construct(
        Reader $reader,
        ScopeManagerInterface $scope_manager,
        AccessTokenTypeManagerInterface $access_token_type_manager,
        AccessTokenManagerInterface $access_token_manager,
        ExceptionManagerInterface $exception_manager,
        SecurityContextInterface $security_context
    ) {
        $this->reader = $reader;
        $this->scope_manager = $scope_manager;
        $this->access_token_type_manager = $access_token_type_manager;
        $this->access_token_manager = $access_token_manager;
        $this->exception_manager = $exception_manager;
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
                //$request = $event->getRequest();
                $token = $this->security_context->getToken();
                $exception = null;

                // If no access token is found by the firewall, then returns an authentication error
                if (!$token instanceof AccessTokenInterface) {
                    $this->createAuthenticationExeption($event, 'OAuth2 authentication required', $configuration->getScope());

                    return;
                }

                // If the scope of the access token are not sufficient, then returns an authentication error
                $tokenScope = $this->scope_manager->convertToScope($token->getScope());
                $requiredScope = $this->scope_manager->convertToScope($configuration->getScope());
                if (!$this->scope_manager->checkScope($requiredScope, $tokenScope)) {
                    $this->createAuthenticationExeption($event, 'Insufficient scope', $configuration->getScope());

                    return;
                }

                // If the client type of the access token is not allowed, then returns an authentication error
                if ($configuration->getClientType()) {
                    $this->createAuthenticationExeption($event, 'Bad client type', $configuration->getScope());

                    return;
                }

                // If the client public id of the access token is not allowed, then returns an authentication error
                if ($configuration->getClientPublicId()) {
                    $this->createAuthenticationExeption($event, 'Unauthorized client', $configuration->getScope());

                    return;
                }

                // If the resource owner public id of the access token is not allowed, then returns an authentication error
                if ($configuration->getResourceOwnerPublicId()) {
                    $this->createAuthenticationExeption($event, 'Unauthorized client', $configuration->getScope());

                    return;
                }
            }
        }
    }

    /**
     * @param \Symfony\Component\HttpKernel\Event\FilterControllerEvent $event
     * @param string                                                    $message
     * @param string[]                                                  $scope
     */
    private function createAuthenticationExeption(FilterControllerEvent &$event, $message, array $scope = [])
    {
        $params = [
            'scope'=>implode(' ', $scope),
            'scheme' => $this->access_token_type_manager->getDefaultAccessTokenType()->getScheme(),
        ];

        $exception = $this->exception_manager->getException(ExceptionManagerInterface::AUTHENTICATE, ExceptionManagerInterface::ACCESS_DENIED, $message, $params);

        $event->setController(function () use ($exception) {
            $response = new Response();
            $exception->getHttpResponse($response);

            return $response;
        });
    }
}

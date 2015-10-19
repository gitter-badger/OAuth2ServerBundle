<?php

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\SecurityPlugin\Annotation;

use Doctrine\Common\Annotations\Reader;
use OAuth2\Behaviour\HasAccessTokenTypeManager;
use OAuth2\Behaviour\HasExceptionManager;
use OAuth2\Exception\ExceptionManagerInterface;
use SpomkyLabs\OAuth2ServerBundle\Plugin\SecurityPlugin\Annotation\Checker\CheckerInterface;
use SpomkyLabs\OAuth2ServerBundle\Plugin\SecurityPlugin\Security\Authentication\Token\OAuth2Token;
use Symfony\Bridge\PsrHttpMessage\Factory\HttpFoundationFactory;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Zend\Diactoros\Response;

class AnnotationDriver
{
    use HasAccessTokenTypeManager;
    use HasExceptionManager;

    /**
     * @var \Doctrine\Common\Annotations\Reader
     */
    private $reader;

    /**
     * @var \Symfony\Component\Security\Core\SecurityContextInterface
     */
    private $token_storage;

    /**
     * @var \SpomkyLabs\OAuth2ServerBundle\Plugin\SecurityPlugin\Annotation\Checker\CheckerInterface[]
     */
    private $checkers = [];

    /**
     * @param \Doctrine\Common\Annotations\Reader                                                 $reader
     * @param \Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface $token_storage
     */
    public function __construct(Reader $reader, TokenStorageInterface $token_storage)
    {
        $this->reader = $reader;
        $this->token_storage = $token_storage;
    }

    public function addChecker(CheckerInterface $checker)
    {
        $this->checkers[] = $checker;
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
                $token = $this->token_storage->getToken();

                // If no access token is found by the firewall, then returns an authentication error
                if (!$token instanceof OAuth2Token) {
                    $this->createAuthenticationException($event, 'OAuth2 authentication required', $configuration->getScope());

                    return;
                }

                foreach ($this->checkers as $checker) {
                    $result = $checker->check($token, $configuration);
                    if (null !== $result) {
                        $this->createAuthenticationException($event, $result, $configuration->getScope());

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
        $schemes = ['schemes' => []];
        foreach ($this->getAccessTokenTypeManager()->getAccessTokenTypes() as $type) {
            $params = $type->getSchemeParameters();
            if (!empty($params)) {
                foreach ($params as $id => $param) {
                    if (!empty($scope)) {
                        $params[$id] = array_merge($params[$id], ['scope' => implode(' ', $scope)]);
                    }
                }
                $schemes['schemes'] = array_merge($schemes['schemes'], $params);
            }
        }

        $exception = $this->getExceptionManager()->getException(ExceptionManagerInterface::AUTHENTICATE, ExceptionManagerInterface::ACCESS_DENIED, $message, $schemes);

        $event->setController(function () use ($exception) {
            $response = new Response();
            $exception->getHttpResponse($response);
            $response->getBody()->rewind();

            $factory = new HttpFoundationFactory();
            $response = $factory->createResponse($response);

            return $response;
        });
    }
}

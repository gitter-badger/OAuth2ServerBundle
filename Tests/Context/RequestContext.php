<?php

namespace SpomkyLabs\OAuth2ServerBundle\Features\Context;

use Behat\Mink\Driver\BrowserKitDriver;

trait RequestContext
{
    private $request_builder = null;
    private $exception = null;

    /**
     * Returns HttpKernel service container.
     *
     * @return \Symfony\Component\DependencyInjection\ContainerInterface
     */
    abstract public function getContainer();

    /**
     * Returns Mink session.
     *
     * @param string|null $name name of the session OR active session will be used
     *
     * @return \Behat\Mink\Session
     */
    abstract public function getSession($name = null);

    /**
     * @param string $uri
     *
     * @return string
     */
    abstract public function locatePath($uri);

    /**
     * @return \SpomkyLabs\OAuth2ServerBundle\Features\Context\RequestBuilder
     */
    protected function getRequestBuilder()
    {
        if (null === $this->request_builder) {
            $this->request_builder = new RequestBuilder();
        }

        return $this->request_builder;
    }

    /**
     * @return null|\Exception
     */
    public function getException()
    {
        return $this->exception;
    }

    /**
     * @Given I add key :key with value :value in the header
     */
    public function iAddKeyWithValueInTheHeader($key, $value)
    {
        $this->getRequestBuilder()->addHeader($key, $value);
    }

    /**
     * @Given I add key :key with value :value in the query parameter
     */
    public function iAddKeyWithValueInTheQueryParameter($key, $value)
    {
        $this->getRequestBuilder()->addQueryParameter($key, $value);
    }

    /**
     * @Given I add user :user and password :secret in the authorization header
     */
    public function iAddUserAndPasswordInTheAuthorizationHeader($user, $secret)
    {
        $this->getRequestBuilder()->addHeader('Authorization', 'Basic '.base64_encode("$user:$secret"));
    }

    /**
     * @Given I add key :key with value :value in the body request
     */
    public function iAddKeyWithValueInTheBodyRequest($key, $value)
    {
        $this->getRequestBuilder()->addRequestParameter($key, $value);
    }

    /**
     * @Given the content type is :content_type
     */
    public function theContentTypeIs($content_type)
    {
        $this->getRequestBuilder()->addServer('CONTENT_TYPE', $content_type);
    }

    /**
     * @Given the request is not secured
     */
    public function theRequestIsNotSecured()
    {
        $this->getRequestBuilder()->addServer('HTTPS', 'off');
    }

    /**
     * @Given the request is secured
     */
    public function theRequestIsSecured()
    {
        $this->getRequestBuilder()->addServer('HTTPS', 'on');
    }

    /**
     * @Given I add key :key with public id of :username in the body request
     */
    public function iAddKeyWithPublicIdOfInTheBodyRequest($key, $username)
    {
        $user = $this->getContainer()->get('oauth2_server.test_bundle.end_user_manager')->getEndUserByUsername($username);

        if (null === $user) {
            throw new \Exception('Unknown user');
        }
        $this->iAddKeyWithValueInTheBodyRequest($key, $user->getPublicId());
    }

    /**
     * @When I :method the request to :uri
     *
     * @param string $method
     */
    public function iTheRequestTo($method, $uri)
    {
        if (!$this->getSession()->getDriver() instanceof BrowserKitDriver) {
            throw new \RuntimeException('Unsupported driver.');
        }
        /**
         * @var $client \Symfony\Component\BrowserKit\Client
         */
        $client = $this->getSession()->getDriver()->getClient();
        $client->followRedirects(false);

        $this->getRequestBuilder()->setUri($this->locatePath($uri));
        try {
            $client->request(
                $method,
                $this->getRequestBuilder()->getUri(),
                $this->getRequestBuilder()->getRequestParameters(),
                [],
                $this->getRequestBuilder()->getServer(),
                $this->getRequestBuilder()->getContent()
            );
        } catch (\Exception $e) {
            $this->exception = $e;
        }
        $client->followRedirects(true);
    }

    /**
     * @Given I am on the page :url
     */
    public function iAmOnThePage($url)
    {
        $this->iTheRequestTo('GET', $url);
    }

    /**
     * @Then I should not receive an exception
     */
    public function iShouldNotReceiveAnException()
    {
        if ($this->getException() instanceof \Exception) {
            throw $this->getException();
        }
    }

    /**
     * @Then I should receive an exception :message
     */
    public function iShouldReceiveAnException($message)
    {
        if (!$this->getException() instanceof \Exception) {
            throw new \Exception('No exception caught');
        }

        if ($message !== $this->getException()->getMessage()) {
            throw new \Exception(sprintf('The exception has not the expected message: "%s"', $message));
        }
    }
}

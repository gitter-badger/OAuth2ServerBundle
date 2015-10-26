<?php

namespace SpomkyLabs\OAuth2ServerBundle\Features\Context;

use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Gherkin\Node\PyStringNode;
use Behat\MinkExtension\Context\MinkContext;
use Behat\Symfony2Extension\Context\KernelDictionary;
use OAuth2\Token\RefreshTokenInterface;
use SpomkyLabs\Jose\EncryptionInstruction;
use SpomkyLabs\Jose\SignatureInstruction;
use SpomkyLabs\OAuth2ServerBundle\Plugin\CorePlugin\Command\CleanCommand;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

/**
 * Behat context class.
 */
class FeatureContext extends MinkContext implements SnippetAcceptingContext
{
    use KernelDictionary;

    private $request_builder;
    private $exception;
    private $application = null;
    private $command_output = null;
    private $oauth2_response = null;

    /**
     * Initializes context.
     *
     * Every scenario gets its own context object.
     * You can also pass arbitrary arguments to the context constructor through behat.yml.
     */
    public function __construct()
    {
        $this->exception = null;
        $this->request_builder = new RequestBuilder();
    }

    public function getRequestBuilder()
    {
        return $this->request_builder;
    }

    public function getException()
    {
        return $this->exception;
    }

    /**
     * @Given I have a valid client assertion for client :client in the body request
     */
    public function IHaveAValidClientAssertionForClientInTheBodyRequest($client)
    {
        /*
         * @var \Jose\JWKManagerInterface
         */
        $key_manager = $this->getContainer()->get('jose.jwk_manager');
        $jwk1 = $key_manager->createJWK([
            'kid' => 'JWK1',
            'kty' => 'oct',
            'use' => 'enc',
            'k'   => 'ABEiM0RVZneImaq7zN3u_wABAgMEBQYHCAkKCwwNDg8',
        ]);
        $jwk2 = $key_manager->createJWK([
            'kid' => 'JWK2',
            'kty' => 'oct',
            'use' => 'sig',
            'k'   => 'AyM1SysPpbyDfgZld3umj1qzKObwVMkoqQ-EstJQLr_T-1qS0gZH75aKtMN3Yj0iPS4hcgUuTwjAzZr1Z9CAow',
        ]);

        $jose = $this->getContainer()->get('jose');

        $input = [
            'exp' => time() + 3600,
            'aud' => 'My Authorization Server',
            'iss' => 'My JWT issuer',
            'sub' => $client,
        ];

        $signature_instruction = new SignatureInstruction();
        $signature_instruction->setKey($jwk2)
            ->setProtectedHeader(['cty' => 'JWT','alg' => 'HS512'])
            ->setUnprotectedHeader([]);

        $encryption_instruction = new EncryptionInstruction();
        $encryption_instruction->setRecipientKey($jwk1);

        $jws = $jose->sign($input, [$signature_instruction]);
        $jwe = $jose->encrypt($jws, [$encryption_instruction], ['cty' => 'JWT', 'alg' => 'A256KW', 'enc' => 'A256CBC-HS512', 'exp' => time() + 3600, 'aud' => 'My Authorization Server', 'iss' => 'My JWT issuer', 'sub' => $client]);

        $this->iAddKeyWithValueInTheBodyRequest('client_assertion_type', 'urn:ietf:params:oauth:client-assertion-type:jwt-bearer');
        $this->iAddKeyWithValueInTheBodyRequest('client_assertion', $jwe);
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
        $this->getRequestBuilder()->addContentParameter($key, $value);
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
     * @Given the response header :header value is :value
     */
    public function theResponseHeaderValueIs($header, $value)
    {
        $headers = $this->getSession()->getResponseHeaders();
        if (!isset($headers[strtolower($header)])) {
            throw new \Exception('There is no header parameter "'.$header.'" in the response');
        }
        if (!in_array($value, $headers[strtolower($header)])) {
            throw new \Exception('Header parameter value is not "'.$value.'"');
        }
    }

    /**
     * @Given the request is secured
     */
    public function theRequestIsSecured()
    {
        $this->getRequestBuilder()->addServer('HTTPS', 'on');
    }

    /**
     * @Given I am logged in as :username
     */
    public function iAmAnLoggedInAs($username)
    {
        $session = new Session();
        $client = $this->getSession()->getDriver()->getClient();

        $client->getCookieJar()->set(new Cookie($session->getName(), $session->getId()));

        $session = $this->getContainer()->get('session');

        $user = $this->getKernel()->getContainer()->get('oauth2_server.test_bundle.end_user_manager')->getEndUserByUsername($username);

        if (null === $user) {
            throw new \Exception('Unknown user');
        }
        $token = new UsernamePasswordToken($user, 'secret', 'main', $user->getRoles());
        $session->set('_security_main', serialize($token));
        $session->save();

        $cookie = new Cookie($session->getName(), $session->getId());
        $client->getCookieJar()->set($cookie);
    }

    /**
     * @When I :method the request to :uri
     *
     * @param string $method
     */
    public function iTheRequestTo($method, $uri)
    {
        $client = $this->getSession()->getDriver()->getClient();
        $client->followRedirects(false);

        $this->getRequestBuilder()->setUri($this->locatePath($uri));
        try {
            $client->request(
                $method,
                $this->getRequestBuilder()->getUri(),
                [],
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
     * @Then I should receive a :content_type response
     */
    public function iShouldReceiveAResponse($content_type)
    {
        $headers = $this->getSession()->getResponseHeaders();
        if (!isset($headers['content-type']) || !in_array($content_type, $headers['content-type'])) {
            throw new \Exception('The response header does not contain "'.$content_type.'"');
        }
    }

    /**
     * @Then I should receive an OAuth2 response
     */
    public function iShouldReceiveAnOauthResponse()
    {
        $headers = $this->getSession()->getResponseHeaders();
        $result = $this->getSession()->getPage()->getContent();
        $data = json_decode($result, true);
        if (!isset($headers['content-type']) && $headers['content-type'] !== 'application/json') {
            throw new \Exception('The result is not an OAuth2 response (not JSON object).');
        }
        if (!is_array($data)) {
            throw new \Exception('The result is not an OAuth2 response (not JSON object).');
        }
        $this->oauth2_response = $data;
    }

    /**
     * @Then the response contains an access token
     */
    public function theResponseContainsAnAccessToken()
    {
        if (null === $this->oauth2_response) {
            throw new \Exception('The response is not an OAuth2 response');
        }
        $content = $this->getSession()->getPage()->getContent();
        $data = json_decode($content, true);
        if (!isset($data['access_token'])) {
            throw new \Exception('The response does not contain an access token');
        }
    }

    /**
     * @Then the response is not an OAuth2 Exception
     */
    public function theResponseIsNotAnOauthException()
    {
        if (null === $this->oauth2_response) {
            throw new \Exception('There is no OAuth2 response');
        }
        if (isset($this->oauth2_response['error'])) {
            throw new \Exception("The response is an OAuth2 Exception. Message is '".$this->oauth2_response['error']."' and description '".$this->oauth2_response['error_description']."'");
        }
    }

    /**
     * @Then the response is a JSON object
     */
    public function theResponseIsAJsonObject()
    {
        $content = $this->getSession()->getPage()->getContent();
        $data = json_decode($content, true);

        if (!$data) {
            throw new \Exception('The content is not a JSON object');
        }
    }

    /**
     * @Then the body contains an access token
     */
    public function theBodyContainsAnAccessToken()
    {
        $content = $this->getSession()->getPage()->getContent();
        $data = json_decode($content, true);

        if (!isset($data['access_token'])) {
            throw new \Exception('The content does not contain an access token');
        }
    }

    /**
     * @Then the status code of the response is :code
     */
    public function theStatusCodeOfTheResponseIs($code)
    {
        if ($this->getSession()->getStatusCode() !== (int) $code) {
            throw new \Exception('The status code of the response is not "'.$code.'". I got "'.$this->getSession()->getStatusCode().'"');
        }
    }

    /**
     * @Then I should receive an OAuth2 exception with message :message and description :scheme
     */
    public function iShouldReceiveAnOauth2ExceptionWithMessageAndDescription($message, $description)
    {
        if ($this->exception instanceof \Exception) {
            throw $this->exception;
        }

        $content = $this->getSession()->getPage()->getContent();
        $data = json_decode($content, true);

        if (!$data) {
            throw new \Exception('The response is not an OAuth2 Exception.');
        }

        if (!isset($data['error_description']) || !isset($data['error'])) {
            throw new \Exception('The response is not an OAuth2 Exception.');
        }

        if ($data['error'] !== $message) {
            if (isset($data['error_description'])) {
                throw new \Exception('The error should be "'.$message.'" but I get "'.$data['error'].'". Description is "'.$data['error_description'].'"');
            } else {
                throw new \Exception('The error should be "'.$message.'" but I get "'.$data['error'].'"');
            }
        }

        if ($data['error_description'] !== $description) {
            throw new \Exception('The error should be "'.$description.'" but I get "'.$data['error_description'].'"');
        }
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
            throw new \Exception('No exception catched');
        }

        if ($message !== $this->getException()->getMessage()) {
            throw new \Exception('The exception has not the expected message: "'.$message.'"');
        }
    }

    /**
     * @Then I should receive an error :message
     */
    public function iShouldReceiveAnError($message)
    {
        $content = $this->getSession()->getPage()->getContent();
        $data = json_decode($content, true);

        if (!isset($data['error'])) {
            throw new \Exception('The response does not contain an error');
        }

        if ($data['error'] !== $message) {
            if (isset($data['error_description'])) {
                throw new \Exception('The response contains an error, but its value is "'.$data['error'].'" and description is "'.$data['error_description'].'"');
            } else {
                throw new \Exception('The response contains an error, but its value is "'.$data['error'].'"');
            }
        }
    }

    /**
     * @Then I should be redirected using the :scheme scheme
     */
    public function iShouldBeRedirectedUsingTheScheme($scheme)
    {
        if ($this->getSession()->getStatusCode() !== 301 && $this->getSession()->getStatusCode() !== 302) {
            throw new \Exception('The status code is not a redirection');
        }

        $header = $this->getSession()->getResponseHeaders();

        if (!isset($header['location'])) {
            throw new \Exception('The header does not contain a redirection URL');
        }

        $uri = parse_url($header['location']);

        if ($scheme !== $uri['scheme']) {
            throw new \Exception('The redirection Uri does not use "'.$scheme.'" scheme');
        }
    }

    /**
     * @Given I am on the page :url
     */
    public function iAmOnThePage($url)
    {
        $this->iTheRequestTo('GET', $url);
    }

    /**
     * @When I click on :arg1
     */
    public function iClickOn($name)
    {
        $content = $this->getSession()->getPage()->getContent();
        print_r(2500<strlen($content)?substr($content,0,2500):$content);
        $this->getSession()->getDriver()->getClient()->followRedirects(false);

        $button = $this->fixStepArgument($name);
        $this->getSession()->getPage()->pressButton($button);

        $this->getSession()->getDriver()->getClient()->followRedirects(true);
    }

    /**
     * @Then I should be redirected
     */
    public function iShouldBeRedirected()
    {
        $headers = $this->getSession()->getResponseHeaders();
        if (!isset($headers['location'])) {
            throw new \Exception('There is no redirection in the response');
        }
    }

    /**
     * @Then the redirect query should contain parameter :param
     */
    public function theRedirectQueryShouldContainParameter($param)
    {
        $headers = $this->getSession()->getResponseHeaders();
        $uri = parse_url(current($headers['location']));

        if (!isset($uri['query'])) {
            throw new \Exception('The query does not contain any parameter');
        }
        parse_str($uri['query'], $uri['query']);
        if (!isset($uri['query'][$param])) {
            throw new \Exception('The query does not contain parameter "'.$param.'"');
        }
    }

    /**
     * @Then the redirect query should contain parameter :param with value :value
     */
    public function theRedirectQueryShouldContainParameterWithValue($param, $value)
    {
        $headers = $this->getSession()->getResponseHeaders();
        $uri = parse_url(current($headers['location']));
        parse_str($uri['query'], $uri['query']);
        if (!isset($uri['query'][$param])) {
            throw new \Exception('The query does not contain parameter "'.$param.'"');
        }

        if ($uri['query'][$param] !== $value) {
            throw new \Exception('The value is not "'.$value.'", I got "'.$uri['query'][$param].'"');
        }
    }

    /**
     * @Then the redirection starts with :location
     */
    public function theRedirectionStartsWith($location)
    {
        $headers = $this->getSession()->getResponseHeaders();
        foreach ($headers['location'] as $url) {
            if (substr($url, 0, strlen($location)) === $location) {
                return;
            }
        }
        throw new \Exception('The redirection URL does not contain "'.$location.'". The complete values are "'.json_encode($headers['location']).'"');
    }

    /**
     * @Then I should receive an access token
     */
    public function iShouldReceiveAnAccessToken()
    {
        $headers = $this->getSession()->getResponseHeaders();
        $content = $this->getSession()->getPage()->getContent();
        $data = json_decode($content, true);

        if (!isset($headers['content-type']) && $headers['content-type'] !== 'application/json') {
            throw new \Exception('The content is not a JSON object');
        }

        if (!is_array($data)) {
            throw new \Exception('The content is not an array');
        }

        if (!isset($data['access_token'])) {
            throw new \Exception('The content is not an access token');
        }
    }

    /**
     * @Then the error should contain parameter :param
     */
    public function theErrorShouldContainParameter($param)
    {
        $content = $this->getSession()->getPage()->getContent();
        $data = json_decode($content, true);

        if (!isset($data[$param])) {
            throw new \Exception('The access token does not contain parameter "'.$param.'"');
        }
    }

    /**
     * @Then the error should contain parameter :param with value :value
     */
    public function theErrorShouldContainParameterWithValue($param, $value)
    {
        $content = $this->getSession()->getPage()->getContent();
        $data = json_decode($content, true);

        if (!isset($data[$param])) {
            throw new \Exception('The access token does not contain parameter "'.$param.'"');
        }

        if ($data[$param] !== $value) {
            throw new \Exception('The access token contains parameter "'.$param.'", but its value is "'.$data[$param].'"');
        }
    }

    /**
     * @Then the error has :param with value :value
     */
    public function theErrorHasWithValue($param, $value)
    {
        $content = $this->getSession()->getPage()->getContent();
        $data = json_decode($content, true);

        if (!isset($data[$param])) {
            throw new \Exception('The access token does not contain parameter "'.$param.'"');
        }

        if ($data[$param] !== $value) {
            throw new \Exception('The access token contains parameter "'.$param.'", but its value is "'.$data[$param].'"');
        }
    }

    /**
     * @Then the access token should contain parameter :param with value :value
     */
    public function theAccessTokenShouldContainParameterWithValue($param, $value)
    {
        $content = $this->getSession()->getPage()->getContent();
        $data = json_decode($content, true);

        if (!isset($data[$param])) {
            throw new \Exception('The access token does not contain parameter "'.$param.'"');
        }

        if ($data[$param] !== $value) {
            throw new \Exception('The access token contains parameter "'.$param.'", but its value is "'.$data[$param].'"');
        }
    }

    /**
     * @When I run :line command
     */
    public function iRunCommand($line)
    {
        if (null === $this->application) {
            $this->application = new Application($this->getKernel());
            $this->application->add(new CleanCommand());
        }

        $command = $this->application->find($line);
        $tester = new CommandTester($command);
        $tester->execute(['command' => $command->getName()]);
        $this->command_output = $tester->getDisplay();
    }

    /**
     * @Then I should see
     */
    public function iShouldSee(PyStringNode $result)
    {
        if ($this->command_output !== $result->getRaw()) {
            throw new \Exception('The output of the command is not the same as expected. I got '.$this->command_output.'');
        }
    }

    /**
     * @Then the redirect query should contain parameter :param with length :value
     */
    public function theRedirectQueryShouldContainParameterWithLength($param, $value)
    {
        $headers = $this->getSession()->getResponseHeaders();
        $uri = parse_url(current($headers['location']));
        parse_str($uri['query'], $uri['query']);
        if (!isset($uri['query'][$param])) {
            throw new \Exception('The query does not contain parameter "'.$param.'"');
        }

        if (mb_strlen($uri['query'][$param]) !== (int) $value) {
            throw new \Exception('The length is not "'.$value.'", I got "'.mb_strlen($uri['query'][$param]).'"');
        }
    }

    /**
     * @Then I should not receive an error
     */
    public function iShouldNotReceiveAnError()
    {
        $content = $this->getSession()->getPage()->getContent();
        $data = json_decode($content, true);

        if (is_array($data) && array_key_exists('error', $data)) {
            if (array_key_exists('error_description', $data)) {
                throw new \Exception(sprintf('I received the error "%s" with description "%s"', $data['error'], $data['error_description']));
            } else {
                throw new \Exception(sprintf('I received the error "%s"', $data['error']));
            }
        }
    }

    /**
     * @Then the redirect fragment should contain parameter :param
     */
    public function theRedirectFragmentShouldContainParameter($param)
    {
        $headers = $this->getSession()->getResponseHeaders();
        foreach ($headers['location'] as $url) {
            $data = parse_url($url);
            if (isset($data['fragment'])) {
                parse_str($data['fragment'], $output);
                if (isset($output[$param])) {
                    return;
                }
            }
        }
        throw new \Exception('The redirection URL does not contain parameter "'.$param.'".');
    }

    /**
     * @Then the redirect fragment should contain parameter :param with value :value
     */
    public function theRedirectFragmentShouldContainParameterWithValue($param, $value)
    {
        $headers = $this->getSession()->getResponseHeaders();
        foreach ($headers['location'] as $url) {
            $data = parse_url($url);
            if (isset($data['fragment'])) {
                parse_str($data['fragment'], $output);
                if (isset($output[$param]) && $output[$param] === $value) {
                    return;
                }
            }
        }
        throw new \Exception('The redirection URL contains parameter "'.$param.'", but its value is "'.$output[$param].'".');
    }

    /**
     * @Then the refresh token :token must be marked as used
     */
    public function theRefreshTokenMustBeMarkedAsUsed($token)
    {
        $token = $this->getContainer()->get('oauth2_server.refresh_token.token_manager')->getRefreshToken($token);
        if (!$token instanceof RefreshTokenInterface || false === $token->isUsed()) {
            throw new \Exception('The token is not an instance of RefreshTokenInterface or is not marked as used');
        }
    }

    /**
     * @Then I should receive an authentication error
     */
    public function iShouldReceiveAnAuthenticationError()
    {
        $headers = $this->getSession()->getResponseHeaders();
        if (!isset($headers['www-authenticate'])) {
            throw new \Exception('The response is not an authentication error.');
        }
    }

    /**
     * @Then the response content is :content
     */
    public function theResponseContentIs($content)
    {
        $result = $this->getSession()->getPage()->getContent();
        if ($result !== $content) {
            throw new \Exception('The content is: '.$result);
        }
    }

    /**
     * @Then the response has no content
     */
    public function theResponseHasNoContent()
    {
        $content = $this->getSession()->getPage()->getContent();
        if (!empty($content)) {
            throw new \Exception('The content is : '.$content);
        }
    }

    /**
     * @Then the access token :token does not exist
     */
    public function theAccessTokenDoesNotExist($token)
    {
        /*
         * @var \OAuth2\Token\AccessTokenManagerInterface
         */
        $token_manager = $this->getKernel()->getContainer()->get('oauth2_server.simple_string_access_token.manager');
        $result = $token_manager->getAccessToken($token);
        if (null !== $result) {
            throw new \Exception('The access token exists.');
        }
    }

    /**
     * @Then the access token :token exists
     */
    public function theAccessTokenExists($token)
    {
        /*
         * @var \OAuth2\Token\AccessTokenManagerInterface
         */
        $token_manager = $this->getKernel()->getContainer()->get('oauth2_server.simple_string_access_token.manager');
        $result = $token_manager->getAccessToken($token);
        if (null === $result) {
            throw new \Exception('The access token does not exist.');
        }
    }

    /**
     * @Then the access token manager has :count access token for client :client_id
     */
    public function theAccessTokenManagerHasAccessTokenForClient($count, $client_id)
    {
        /*
         * @var \SpomkyLabs\OAuth2ServerBundle\Plugin\SimpleStringAccessTokenPlugin\Model\SimpleStringAccessTokenManager
         */
        $access_token_manager = $this->getKernel()->getContainer()->get('oauth2_server.simple_string_access_token.manager');
        $access_tokens = $access_token_manager->getEntityRepository()->findBy(['client_public_id' => $client_id]);

        if ((int) $count !== count($access_tokens)) {
            throw new \Exception(sprintf('I have not exactly %s access token(s). I have %d.', $count, count($access_tokens)));
        }
    }

    /**
     * @Then the www-authenticate header parameter :id value is :value
     */
    public function theIs($id, $value)
    {
        $headers = $this->getSession()->getResponseHeaders();
        $authentication = substr(current($headers['www-authenticate']), 7);
        preg_match_all('@('.$id.')=(?:([\'"])([^\2]+?)\2|([^\s,]+))@', $authentication, $matches, PREG_SET_ORDER);
        if (1 !== count($matches)) {
            throw new \Exception('There is no key "'.$id.'"');
        }
        if (1 !== count($matches) || $value !== $matches[0][3]) {
            throw new \Exception('The '.$id.' is "'.$matches[0][3].'" ("'.$value.'" expected)');
        }
    }

    /**
     * @Then the www-authenticate header has no :parameter parameter
     */
    public function theWwwAuthenticateHeaderHasNoParameter($parameter)
    {
        $headers = $this->getSession()->getResponseHeaders();
        $authentication = substr(current($headers['www-authenticate']), 7);
        preg_match_all('@('.$parameter.')=(?:([\'"])([^\2]+?)\2|([^\s,]+))@', $authentication, $matches, PREG_SET_ORDER);
        if (0 < count($matches)) {
            throw new \Exception('There is a parameter "'.$parameter.'". Its values are "%s"', json_encode($matches));
        }
    }

    /**
     * @Given I add key :key with public id of :username in the body request
     */
    public function iAddKeyWithPublicIdOfInTheBodyRequest($key, $username)
    {
        $user = $this->getKernel()->getContainer()->get('oauth2_server.test_bundle.end_user_manager')->getEndUserByUsername($username);

        if (null === $user) {
            throw new \Exception('Unknown user');
        }
        $this->iAddKeyWithValueInTheBodyRequest($key, $user->getPublicId());
    }
}

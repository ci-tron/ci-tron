<?php
/**
 * This file is a part of ci-tron package.
 *
 * (c) Ci-tron <dev@ci-tron.org>
 *
 * For the full license, take a look to the LICENSE file
 * on the root directory of this project
 */

namespace CiTron\Behat;

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Guzzle\Http\Exception\ClientErrorResponseException;
use CiTron\Behat\Exception\ResponseException;
use GuzzleHttp\Exception\BadResponseException;
use Knp\FriendlyContexts\Context\Context as FriendlyContext;

/**
 * Defines application features from the specific context.
 */
class FeatureContext extends FriendlyContext implements Context, SnippetAcceptingContext
{
    /**
     * @var \Knp\FriendlyContexts\Context\ApiContext
     */
    private $apiContext;

    /**
     * @var string
     */
    private $csrfToken;

    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
    }

    /**
     * @BeforeScenario
     */
    public function gatherContexts(\Behat\Behat\Hook\Scope\BeforeScenarioScope $scope)
    {
        $environment = $scope->getEnvironment();
        $this->apiContext = $environment->getContext('Knp\\FriendlyContexts\\Context\\ApiContext');
    }

    /**
     * @Given I am logged with username :username and password :password
     */
    public function iAmLoggedWithUsernameAndPassword($username, $password)
    {
        try {
            $response = $this
                ->getRequestBuilder()
                ->setMethod('POST')
                ->setUri('/back/login')
                ->setBody(['username' => $username, 'password' => $password])
                ->build()
                ->send()
            ;
        } catch (ClientErrorResponseException $e) {
            $response = $e->getResponse();
        }

        if (200 !== $response->getStatusCode()) {
            throw new ResponseException($response);
        }
    }

    /**
     * @Given retrieve a CSRF token for :arg1
     */
    public function retrieveACsrfTokenFor($arg1)
    {
        try {
            $response = $this
                ->getRequestBuilder()
                ->setMethod('GET')
                ->setUri('/back/security/csrf/' . $arg1 . '.json')
                ->build()
                ->send()
            ;
        } catch (BadResponseException $e) {
            $response = $e->getResponse();
        }

        if (200 !== $response->getStatusCode()) {
            throw new ResponseException($response);
        }

        $body = json_decode((string) $response->getBody(), true);
        if (false !== $body) {
            if (empty($body['token'])) {
                throw new \Exception('The token is empty.');
            }
            $this->csrfToken = $body['token'];
        }
    }

    /**
     * @When I use the last CSRF token
     */
    public function iUseTheLastCsrfToken()
    {
        $queries = $this->getRequestBuilder()->getQueries() ?: [];
        $queries['csrf_token'] = $this->csrfToken;
        $this->getRequestBuilder()->setQueries($queries);
    }

    /**
     * @When print last api response
     */
    public function printLastResponse()
    {
        $response = $this->apiContext->getResponse();

        // In case the body is too large and the content is HTML, we show the result in a browser
        if ($response->isContentType('text/html; charset=UTF-8')) {
            $body = $response->getBody(true);

            if (strlen($body)>1000) {
                $filepath = sys_get_temp_dir() . '/' . 'citron_' . uniqid() . '.html';
                file_put_contents($filepath, $body);
                exec(sprintf('firefox %s', $filepath));

                return;
            }
        }

        echo $response;
    }

    /**
     * Reset session by deleting the session cookie
     * Notice: this will delete ALL cookies.
     *
     * @BeforeScenario @reset-session
     */
    public function resetSession()
    {
        $this->container->get('citron.guzzle.array_cookie_jar')->remove(null, '/');
    }
}

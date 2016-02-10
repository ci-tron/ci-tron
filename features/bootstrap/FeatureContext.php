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
        $this->apiContext = $environment->getContext('Knp\FriendlyContexts\Context\ApiContext');
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
                ->setUri('/login')
                ->setBody(['username' => $username, 'password' => $password])
                ->build()
                ->send()
            ;
        } catch (BadResponseException $e) {
            $response = $e->getResponse();
        }

        if (200 !== $response->getStatusCode()) {
            throw new \Exception(sprintf(
                "The login failed with, status code %s and content:\n%s",
                $response->getStatusCode(),
                (string) $response->getBody()
            ));
        }
    }


    /**
     * @When print last response
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

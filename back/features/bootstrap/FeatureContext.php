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

/**
 * Defines application features from the specific context.
 */
class FeatureContext implements Context, SnippetAcceptingContext
{
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
     * @var \Knp\FriendlyContexts\Context\ApiContext
     */
    private $apiContext;

    /**
     * @BeforeScenario
     */
    public function gatherContexts(\Behat\Behat\Hook\Scope\BeforeScenarioScope $scope)
    {
        $environment = $scope->getEnvironment();
        $this->apiContext = $environment->getContext('Knp\FriendlyContexts\Context\ApiContext');
    }

    /**
     * @When print last response
     */
    public function printLastResponse()
    {
        echo $this->apiContext->getResponse();
    }
}

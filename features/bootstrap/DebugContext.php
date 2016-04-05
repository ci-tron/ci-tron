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
use Behat\Behat\Hook\Scope\AfterStepScope;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Behat\Behat\Tester\Result\StepResult;
use Behat\Symfony2Extension\Context\KernelAwareContext;
use Behat\Symfony2Extension\Context\KernelDictionary;

class DebugContext implements Context, KernelAwareContext
{
    use KernelDictionary;

    /**
     * @var \CiTron\Behat\CiTronMinkContext
     */
    private $minkContext;

    /**
     * @BeforeScenario
     */
    public function gatherContexts(BeforeScenarioScope $scope)
    {
        $environment = $scope->getEnvironment();

        $this->minkContext = $environment->getContext('CiTron\Behat\CiTronMinkContext');
    }

    /**
     * Send an email to the team after step failed with error message, screen, and generated html.
     *
     * @AfterStep
     */
    public function dumpInfoAfterFailedStep(AfterStepScope $scope)
    {
        if ($scope->getTestResult()->getResultCode() === StepResult::FAILED && null !== $this->getScenario($scope)) {
            $error = nl2br($scope->getStep()->getText()) . '<br /><br /><b style="color: red">Failed with message : ' .
                $scope->getTestResult()->getException()->getMessage() .
                '</b><br />'
            ;
            $message = \Swift_Message::newInstance()
                ->setFrom('travis@ci-tron.org')
                ->setTo('travis@ci-tron.org')
                ->setSubject('Error on scenario: ' . $this->getScenario($scope)->getTitle())
            ;

            if ($this->minkContext->getSession()->getDriver() instanceof \Behat\Mink\Driver\Selenium2Driver) {
                $screenshot = $this->minkContext->getSession()->getDriver()->getScreenshot();
                $message->attach(\Swift_Attachment::newInstance($screenshot, 'screenshot.png', 'image/png'));
            }

            $error .= '<br /><br />Driver: "' . get_class($this->minkContext->getSession()->getDriver()) . '"';
            $message->attach(\Swift_Attachment::newInstance(
                $this->minkContext->getSession()->getPage()->getHtml(),
                'page.html',
                'text/html'
            ));

            $message->setBody(
                '<html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /></head><body>' .
                $error .
                '</body></html>',
                'text/html'
            );

            $transport = \Swift_SmtpTransport::newInstance('smtp.mailgun.org', 587)
                ->setUsername('test@sandbox90ea023f482a4f3faf810cca3a5febb8.mailgun.org')
                ->setPassword($this->getSmtpPassword())
            ;
            $mailer = \Swift_Mailer::newInstance($transport);


            $mailer->send($message);
        }
    }

    /**
     * @param AfterStepScope $scope
     * @return \Behat\Gherkin\Node\ScenarioInterface|null
     */
    private function getScenario(AfterStepScope $scope)
    {
        $scenarios = $scope->getFeature()->getScenarios();
        $stepLine = $scope->getStep()->getLine();
        $scenario = null;

        foreach($scenarios as $scenarioTmp) {
            if ($scenarioTmp->getLine() < $stepLine) {
                $scenario = $scenarioTmp;
            }
        }

        return $scenario;
    }

    private function getSmtpPassword()
    {
        $pass = getenv('TRAVIS_SMTP_PASSWORD');

        if (!$pass) {
            throw new \Exception('Impossible to send email, reason: impossible to get smtp password.');
        }

        return $pass;
    }
}

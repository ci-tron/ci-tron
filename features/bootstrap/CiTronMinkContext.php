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


use Behat\Gherkin\Node\TableNode;
use Knp\FriendlyContexts\Context\MinkContext;

/**
 * Class CiTronMinkContext
 *
 * The matter of this class is to fix the MinkContext of behat to follow our needs.
 * This needs are mainly introduce by the usage of angularjs.
 */
class CiTronMinkContext extends MinkContext
{
    public function assertPageContainsText($text)
    {
        $this->spin('assertPageContainsText', [$text]);
    }

    public function fillFields(TableNode $fields)
    {
        $this->spin('fillFields', [$fields]);
    }

    public function iAmOnHomepage()
    {
        $this->spin('iAmOnHomepage');
    }

    /**
     * In context of angular, every action is an ajax call that can take some time.
     * This function will execute the test many times until it passes or timeout.
     *
     * @param callable $lambda
     * @param int      $wait   Timeout in seconds.
     * @return bool
     * @throws \Exception
     */
    public function spin ($lambda, array $args = [], int $wait = 10)
    {
        if (!is_string($lambda) && !is_callable($lambda)) {
            throw new \InvalidArgumentException(
                sprintf('The spin method needs a string of parent method or a callable. "%s" given.', gettype($lambda))
            );
        }



        for ($i = 1; $i <= $wait; $i++)
        {
            // When timeout is over, we throw potential exception
            if ($i === $wait) {
                if ($this->runLambda($lambda, $args)) {
                    return true;
                }
            }

            try {
                if ($this->runLambda($lambda, $args)) {
                    return true;
                }
            } catch (\Exception $e) {
                // Do nothing
            }

            sleep(1);
        }

        $backtrace = debug_backtrace();

        throw new \Exception(
            "Timeout thrown by " . $backtrace[1]['class'] . "::" . $backtrace[1]['function'] . "()"
        );
    }
    
    private function runLambda($lambda, array $args) : bool
    {
        $evalArgs = [];
        foreach($args as $i => $arg) {
            $varName = 'EvalArg'.$i;
            $evalArgs[] = '$' . $varName;
            $$varName = $arg;
        }
        $args[] = $this;

        if (is_string($lambda)) {
            $res = eval('parent::$lambda('.implode(',', $evalArgs).');');
        } else {
            $res = call_user_func_array($lambda, $args);
        }

        if ($res === true || $res === null) {
            return true;
        }

        return false;
    }
}

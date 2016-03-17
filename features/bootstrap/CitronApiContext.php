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

use Knp\FriendlyContexts\Context\ApiContext;

class CitronApiContext extends ApiContext
{
    /**
     * @Then /^the creation response should contains the following json:?$/
     */
    public function theCreationResponseShouldContainsJson($jsonData)
    {
        if (!is_object($jsonData)) {
            throw new \InvalidArgumentException('Invalid json data');
        }

        $this->theResponseShouldContainsJson($jsonData, ['id' => 0]);
    }

    /**
     * @Then /^the generated response should contains the following json:?$/
     */
    public function theResponseShouldContainsJson($jsonData, $generatedValues = [])
    {
        if (!is_object($jsonData)) {
            throw new \InvalidArgumentException('Invalid json data');
        }

        $response = $this->response->json();

        $json = false;

        if (!empty($generatedValues)) {
            array_walk_recursive($response, [$this, 'changeGeneratedValues'], $generatedValues);
        }

        parent::theResponsShouldContainsJson($jsonData);
    }

    /**
     * @param $element
     * @param $key
     * @param $generatedValues
     */
    private function changeGeneratedValues(&$element, $key, $generatedValues)
    {
        if (array_key_exists($key, $generatedValues)) {
            $element = $generatedValues[$key];
        }
    }
}

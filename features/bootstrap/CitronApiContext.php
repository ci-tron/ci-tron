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

use Behat\Gherkin\Node\PyStringNode;
use Knp\FriendlyContexts\Context\ApiContext;

class CitronApiContext extends ApiContext
{
    const TYPE_INT = 0;
    const TYPE_STRING = 'string';
    const TYPE_BOOL = true;

    /**
     * @Then /^the creation response should contains the following json:?$/
     */
    public function theCreationResponseShouldContainsJson(PyStringNode $jsonData)
    {
        if (!is_object($jsonData)) {
            throw new \InvalidArgumentException('Invalid json data');
        }

        $this->theResponseShouldContainsJson($jsonData, ['id' => CitronApiContext::TYPE_INT]);
    }

    /**
     * @Then /^the generated response should contains the following json:?$/
     */
    public function theResponseShouldContainsJson(PyStringNode $expected, array $generatedValues = [])
    {
        if (!is_object($expected)) {
            throw new \InvalidArgumentException('Invalid json data');
        }

        if (!empty($generatedValues)) {
            $response = $this->response->json();
            $expected = json_decode($expected->getRaw(), true);


            array_walk_recursive($response, [$this, 'changeGeneratedValues'], $generatedValues);
            array_walk_recursive($expected, [$this, 'changeGeneratedValues'], $generatedValues);

            $expected = json_decode(json_encode($expected), false);
            $this->response->setBody(json_encode($response));
        }


        parent::theResponsShouldContainsJson($expected);
    }

    /**
     * @param $element
     * @param $key
     * @param $generatedValues
     */
    private function changeGeneratedValues(&$element, $key, $generatedValues)
    {
        if (
            array_key_exists($key, $generatedValues) &&
            gettype($element) === gettype($generatedValues[$key])
        ) {
            $element = $generatedValues[$key];
        }
    }
}

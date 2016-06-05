<?php

/**
 * This file is a part of ci-tron package.
 *
 * (c) Ci-tron <dev@ci-tron.org>
 *
 * For the full license, take a look to the LICENSE file
 * on the root directory of this project
 */

namespace CiTron\Project\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class ArrayToJsonDataTransformer implements DataTransformerInterface
{
    /**
     * @param array $value
     * @return string
     */
    public function reverseTransform($value)
    {
        if (null === $value) {
            return '';
        }

        if (!is_array($value)) {
            throw new TransformationFailedException();
        }

        return json_encode($value);
    }

    /**
     * @param string $value
     * @return array|string
     */
    public function transform($value)
    {
        if (null === $value) {
            return '';
        }

        return json_decode($value);
    }
}

<?php

namespace CiTron\Symfony\HttpFoundation;

use Symfony\Component\HttpFoundation\JsonResponse as BaseJsonResponse;

/**
 * Class JsonResponse
 *
 * This class add a new method to JsonResponse in order to be able to add json from JMS Serializer (for example).
 */
class JsonResponse extends BaseJsonResponse
{
    public function setJsonData($json)
    {
        $this->data = $json;

        return $this->update();
    }
}

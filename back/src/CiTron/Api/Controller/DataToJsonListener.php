<?php

namespace CiTron\Api\Controller;

use CiTron\Symfony\HttpFoundation\JsonResponse;
use JMS\Serializer\Serializer;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;

/**
 * Class DataToJsonListener
 *
 * This class transforms data returned by the controller to json.
 */
class DataToJsonListener
{
    /**
     * @var Serializer JMSSerializer
     */
    private $serializer;

    public function __construct(Serializer $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * Transform data from controller to json.
     *
     * @param GetResponseForControllerResultEvent $event
     */
    public function onView(GetResponseForControllerResultEvent $event)
    {
        $controllerResult = $event->getControllerResult();

        $response = new JsonResponse();
        $response->setJsonData($this->serializer->serialize($controllerResult, 'json'));

        $event->setResponse($response);
    }
}

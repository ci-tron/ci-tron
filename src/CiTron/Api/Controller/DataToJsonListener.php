<?php

namespace CiTron\Api\Controller;

use CiTron\Symfony\HttpFoundation\JsonResponse;
use JMS\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Response;
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

        if (is_string($controllerResult)) {
            if (null === json_decode($controllerResult)) {
                $event->setResponse(new Response($controllerResult));

                return;
            }
        } else {
            $controllerResult = $this->serializer->serialize($controllerResult, 'json');
        }

        $response = new JsonResponse();
        $response->setJsonData($controllerResult);
        $event->setResponse($response);
    }
}

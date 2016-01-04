<?php

namespace CiTron\Api\Controller;


use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;

/**
 * Class JsonErrorsListener
 *
 * This listener transforms errors pages (like 404) to JSON errors only in production environment.
 */
class JsonErrorsListener
{
    /**
     * @var string
     */
    private $env;

    /**
     * JsonErrorsListener constructor.
     * @param string $environment global environment of the application
     */
    public function __construct(string $environment)
    {
        $this->env = $environment;
    }

    /**
     * Modify the response to a json response if the status is not 200.
     * (so this allows any type of 200 answer but only json errors)
     *
     * @param FilterResponseEvent $event
     */
    public function onKernelResponse(FilterResponseEvent $event)
    {
        $response = $event->getResponse();
        if ($this->env === 'prod' && !$response instanceof JsonResponse && $response->getStatusCode() !== 200) {
            $event->setResponse((new JsonResponse(['error' => $response->getStatusCode()], $response->getStatusCode())));
        }
    }
}

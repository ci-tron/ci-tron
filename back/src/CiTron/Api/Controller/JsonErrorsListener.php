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

    public function onKernelResponse(FilterResponseEvent $event)
    {
        $response = $event->getResponse();
        if ($this->env === 'prod' && $response->getStatusCode() !== 200) {
            $event->setResponse((new JsonResponse(['error' => $response->getStatusCode()], $response->getStatusCode())));
        }
    }
}

<?php

namespace MNC\Bundle\ProblemDetailsBundle\EventSubscriber;

use MNC\Bundle\ProblemDetailsBundle\ExceptionNormalizer\ExceptionNormalizerInterface;
use MNC\ProblemDetails\ApiExceptionInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * This event subscriber listens for the Kernel Exception event, and tries to
 * capture exceptions and transform them into a Response.
 * @package MNC\Bundle\ProblemDetailsBundle\EventSubscriber
 * @author Matías Navarro Carter <mnavarro@option.cl>
 */
class ApiExceptionSubscriber implements EventSubscriberInterface
{
    const CONTENT_TYPE = 'application/problem+json';
    /**
     * @var ExceptionNormalizerInterface
     */
    private $normalizer;
    /**
     * @var string
     */
    private $env;
    /**
     * @var bool
     */
    private $normalizeInDev;

    public function __construct(ExceptionNormalizerInterface $normalizer, string $env, bool $normalizeInDev = false)
    {
        $this->normalizer = $normalizer;
        $this->env = $env;
        $this->normalizeInDev = $normalizeInDev;
    }

    public static function getSubscribedEvents()
    {
        return [KernelEvents::EXCEPTION => 'onKernelException'];
    }

    /**
     * @param GetResponseForExceptionEvent $event
     * @return GetResponseForExceptionEvent
     */
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $exeption = $event->getException();
        if ($exeption instanceof ApiExceptionInterface) {
            $event->setResponse($this->createResponseFromApiException($exeption));
            $event->stopPropagation();
            return $event;
        }

        if ($this->env === 'dev' AND $this->normalizeInDev === true) {
            $data = $this->normalizer->normalize($exeption);
            if ($data !== null) {
                $response = JsonResponse::create($data, $data['status'], [
                    'Content-Type' => self::CONTENT_TYPE
                ]);
                $event->setResponse($response);
                $event->stopPropagation();
            }
        }

        return $event;
    }

    /**
     * @param $exeption
     * @return JsonResponse
     */
    private function createResponseFromApiException(ApiExceptionInterface $exeption)
    {
        return JsonResponse::create($exeption, $exeption->getStatusCode(), [
            'Content-Type' => self::CONTENT_TYPE
        ]);
    }

}
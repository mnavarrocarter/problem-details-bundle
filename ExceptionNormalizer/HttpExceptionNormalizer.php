<?php

namespace MNC\Bundle\ProblemDetailsBundle\ExceptionNormalizer;

use MNC\ProblemDetails\ApiException;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class SymfonyHttpExceptionNormalizer
 * @package MNC\Bundle\ProblemDetailsBundle\ExceptionNormalizer
 * @author Matías Navarro Carter <mnavarro@option.cl>
 */
class HttpExceptionNormalizer implements ExceptionNormalizerInterface
{
    /**
     * @param \Exception $exception
     * @return array
     */
    public function normalize(\Exception $exception): ?array
    {
        if (!$exception instanceof HttpException) {
            return null;
        }

        return [
            'type' => ApiException::STATUS_CODES_URL,
            'title' => ApiException::$statusTexts[$exception->getStatusCode()],
            'status' => $exception->getStatusCode(),
            'detail' => $exception->getMessage(),
        ];
    }
}
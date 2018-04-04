<?php

namespace MNC\Bundle\ProblemDetailsBundle\ExceptionNormalizer;

use MNC\ProblemDetails\ApiException;

/**
 * Class PhpExceptionNormalizer
 * @package MNC\Bundle\ProblemDetailsBundle\ExceptionNormalizer
 * @author Matías Navarro Carter <mnavarro@option.cl>
 */
class PhpExceptionNormalizer implements ExceptionNormalizerInterface
{
    /**
     * @param \Exception $exception
     * @return array|null
     */
    public function normalize(\Exception $exception): ?array
    {
        return [
            'type' => ApiException::STATUS_CODES_URL,
            'title' => ApiException::$statusTexts[500],
            'status' => 500,
            'details' => 'Your request could not be processed due to a server error.',
        ];
    }

}
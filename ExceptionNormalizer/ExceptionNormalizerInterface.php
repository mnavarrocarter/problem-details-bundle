<?php

namespace MNC\Bundle\ProblemDetailsBundle\ExceptionNormalizer;

/**
 * Interface ExceptionNormalizerInterface
 * @package MNC\Bundle\ProblemDetailsBundle\ExceptionNormalizer
 * @author Matías Navarro Carter <mnavarro@option.cl>
 */
interface ExceptionNormalizerInterface
{
    /**
     * @param \Exception $exception
     * @return array|null
     */
    public function normalize(\Exception $exception): ?array;
}
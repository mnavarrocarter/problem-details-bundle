<?php

namespace MNC\Bundle\ProblemDetailsBundle\ExceptionNormalizer;

/**
 * Class ChainExceptionNormalizer
 * @package MNC\Bundle\ProblemDetailsBundle\ExceptionNormalizer
 * @author Matías Navarro Carter <mnavarro@option.cl>
 */
class ChainExceptionNormalizer implements ExceptionNormalizerInterface
{
    /**
     * @var ExceptionNormalizerInterface[]
     */
    private $normalizers;

    public function __construct(array $normalizers = [])
    {
        $this->normalizers = $normalizers;
    }

    /**
     * @param \Exception $exception
     * @return array|null
     */
    public function normalize(\Exception $exception): ?array
    {
        foreach ($this->normalizers as $normalizer) {
            $array = $normalizer->normalize($exception);
            if ($array !== null) {
                return $array;
            }
        }
        return null;
    }
}
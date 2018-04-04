<?php

namespace MNC\Bundle\ProblemDetailsBundle\ExceptionNormalizer;
use MNC\ProblemDetails\ApiException;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

/**
 * Class AuthExceptionNormalizer
 * @package MNC\Bundle\ProblemDetailsBundle\ExceptionNormalizer
 * @author Matías Navarro Carter <mnavarro@option.cl>
 */
class AuthExceptionNormalizer implements ExceptionNormalizerInterface
{
    /**
     * @param \Exception $exception
     * @return array|null
     */
    public function normalize(\Exception $exception): ?array
    {
        if (!$exception instanceof AuthenticationException) {
            return null;
        }

        $data = [
            'type' => ApiException::STATUS_CODES_URL,
            'title' => ApiException::$statusTexts[401],
            'status' => 401,
            'detail' => $exception->getMessageKey(),
        ];

        $username = $exception->getToken()->getUsername() ?? null;

        if ($username !== null) {
            $data['username'] = $username;
        }

        return $data;
    }
}
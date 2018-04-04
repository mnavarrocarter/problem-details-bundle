<?php

namespace MNC\Bundle\ProblemDetailsBundle\ExceptionNormalizer;

use Doctrine\DBAL\Exception\DriverException;

/**
 * Class DriverExceptionNormalizer
 * @package MNC\Bundle\ProblemDetailsBundle\ExceptionNormalizer
 * @µe
 */
class DriverExceptionNormalizer implements ExceptionNormalizerInterface
{
    const SQLSTATE_ERRORS = 'https://www.ibm.com/support/knowledgecenter/en/SSGU8G_12.1.0/com.ibm.sqls.doc/ids_sqs_0809.htm';

    /**
     * @param \Exception $exception
     * @return array|null
     */
    public function normalize(\Exception $exception): ?array
    {
        if (!$exception instanceof DriverException) {
            return null;
        }

        return [
            'type' => self::SQLSTATE_ERRORS,
            'title' => 'Database Error.',
            'status' => 500,
            'message' => 'Your request could not be processed due to a database error.',
            'driver_error' => $exception->getErrorCode(),
            'sqlstate' => $exception->getSQLState(),
        ];
    }
}
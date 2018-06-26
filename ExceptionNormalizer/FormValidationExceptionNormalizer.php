<?php

namespace MNC\Bundle\ProblemDetailsBundle\ExceptionNormalizer;

use MNC\ProblemDetails\ApiException;
use Symfony\Component\Form\FormInterface;

/**
 * Class FormValidationExceptionNormalizer
 * @package MNC\Bundle\ProblemDetailsBundle\ExceptionNormalizer
 * @author Matías Navarro Carter <mnavarro@option.cl>
 */
class FormValidationExceptionNormalizer implements ExceptionNormalizerInterface
{
    public function normalize(\Exception $exception): ?array
    {
        if (!method_exists($exception, 'getForm')) {
            return null;
        }

        return [
            'type' => ApiException::STATUS_CODES_URL,
            'title' => ApiException::$statusTexts[$exception->getStatusCode()],
            'status' => $exception->getStatusCode(),
            'detail' => $exception->getMessage(),
            'errors' => $this->convertFormToArray($exception->getForm()),
        ];
    }

    private function convertFormToArray($getForm)
    {
        $form = $errors = [];
        foreach ($data->getErrors() as $error) {
            $errors[] = $error->getMessage();
        }
        if ($errors) {
            $form['errors'] = $errors;
        }
        $children = [];
        foreach ($data->all() as $child) {
            if ($child instanceof FormInterface) {
                $children[$child->getName()] = $this->convertFormToArray($child);
            }
        }
        if ($children) {
            $form['children'] = $children;
        }
        return $form;
    }
}
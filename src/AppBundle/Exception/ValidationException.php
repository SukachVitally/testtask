<?php

namespace AppBundle\Exception;

use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class ValidationException
 */
class ValidationException extends HttpException implements
    AppBundleExceptionInterface,
    ValidationExceptionInterface
{
    /**
     * @param array $errors
     */
    public function __construct(array $errors)
    {
        $message = json_encode([
            'message' => 'Sorry, something went wrong. We cannot complete this operation at this time. Code: Invalid request parameters.',
            'errors'  => $errors,
        ]);
        parent::__construct(Code::VALIDATION_ERROR, $message);
    }
}

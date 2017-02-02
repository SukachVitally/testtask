<?php

namespace AppBundle\Exception;

use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class ConflictException
 */
class ConflictException extends HttpException implements
    AppBundleExceptionInterface,
    ValidationExceptionInterface
{
    /**
     * @param array $errors
     */
    public function __construct(array $errors)
    {
        $message = json_encode([
            'message' => 'Sorry, something went wrong. We cannot complete this operation at this time. Code: Entity already exists.',
            'errors'  => $errors,
        ]);
        parent::__construct(Code::CONFLICT_ERROR, $message);
    }
}

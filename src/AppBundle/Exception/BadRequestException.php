<?php

namespace AppBundle\Exception;

use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * BadRequestHttpException.
 */
class BadRequestException extends HttpException implements AppBundleExceptionInterface
{
    /**
     * @param string $message
     */
    public function __construct($message)
    {
        parent::__construct(Code::VALIDATION_ERROR, $message);
    }
}

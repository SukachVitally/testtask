<?php

namespace AppBundle\Handler\Rest;

use FOS\RestBundle\Util\ExceptionWrapper;
use FOS\RestBundle\View\ExceptionWrapperHandlerInterface;

/**
 * Class ExceptionWrapperHandler
 */
class ExceptionWrapperHandler implements ExceptionWrapperHandlerInterface
{
    /**
     * {@inheritdoc}
     */
    public function wrap($data)
    {
        if (!isset($data['exception'])) {
            return new ExceptionWrapper($data);
        }

        $exception = $data['exception'];

        if ($exception->getClass() == 'AppBundle\Exception\ValidationException') {
            $customMessage = json_decode($exception->getMessage(), true);

            $data['message'] = $customMessage['message'];
            $data['errors'] = $customMessage['errors'];
        }

        return new ExceptionWrapper($data);
    }
}

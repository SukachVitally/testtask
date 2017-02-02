<?php

namespace AppBundle\Exception;

/**
 * Class ModelException
 */
class ModelException extends \DomainException implements AppBundleExceptionInterface
{
    /**
     * @return int|mixed
     */
    public function getStatusCode()
    {
        return $this->getCode();
    }
}

<?php

namespace AppBundle\Model;

use JMS\Serializer\Annotation;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class User
 *
 * @Annotation\AccessType("public_method")
 */
class User
{
    /**
     * @var string
     *
     * @Annotation\Type("string")
     */
    private $id;

    /**
     * @var string
     * @Annotation\Type("string")
     * @Assert\Length(
     *      min = 2,
     *      max = 10,
     *      minMessage = "First name must be between 2 and 10 characters long.",
     *      maxMessage = "First name must be between 2 and 10 characters long."
     * )
     */
    private $username;

    /**
     * @var string
     *
     * @Annotation\Type("string")
     */
    private $apiKey;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return User
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Set username
     *
     * @param string $username
     *
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @return string
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }

    /**
     * @param string $apiKey
     * @return User
     */
    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;

        return $this;
    }
}

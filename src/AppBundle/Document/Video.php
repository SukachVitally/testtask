<?php

namespace AppBundle\Document;


use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document(collection="video", repositoryClass="AppBundle\Repository\VideoRepository")
 */
class Video
{
    /**
     * @MongoDB\Id
     */
    private $id;

    /**
     * @MongoDB\Field(type="integer")
     */
    private $startTime;

    /**
     * @MongoDB\Field(type="integer")
     */
    private $endTime;

    /**
     * @MongoDB\Field(type="string")
     */
    private $status;

    /**
     * @MongoDB\Field(type="string")
     */
    private $userId;

    /**
     * @MongoDB\Field(type="string")
     */
    private $extension;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set start time
     *
     * @param string $startTime
     *
     * @return Video
     */
    public function setStartTime($startTime)
    {
        $this->startTime = $startTime;

        return $this;
    }

    /**
     * Get start time
     *
     * @return string
     */
    public function getStartTime()
    {
        return $this->startTime;
    }

    /**
     * Set end time
     *
     * @param integer $endTime
     *
     * @return Video
     */
    public function setEndTime($endTime)
    {
        $this->endTime = $endTime;

        return $this;
    }

    /**
     * Get end time
     *
     * @return integer
     */
    public function getEndTime()
    {
        return $this->endTime;
    }


    /**
     * Set status
     *
     * @param string $status
     *
     * @return Video
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set user id
     *
     * @param string $userId
     *
     * @return Video
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Get user id
     *
     * @return string
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set extension
     *
     * @param string $extension
     *
     * @return Video
     */
    public function setExtension($extension)
    {
        $this->extension = $extension;

        return $this;
    }

    /**
     * Get extension
     *
     * @return string
     */
    public function getExtension()
    {
        return $this->extension;
    }

}

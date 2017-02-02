<?php

namespace AppBundle\Model;

use Symfony\Component\Validator\Constraints as Assert;
use AppBundle\Validator\Constraints as AppAssert;
use Symfony\Component\HttpFoundation\File\File;
use JMS\Serializer\Annotation;

/**
 * Class Video
 */
class Video
{
    const STATUS_SCHEDULED = 'scheduled';
    const STATUS_PROCESSING = 'processing';
    const STATUS_FAILED = 'failed';
    const STATUS_DONE = 'done';

    /**
     * @var string
     *
     * @Annotation\Type("string")
     */
    private $id;

    /**
     * @var integer
     *
     * @Annotation\Type("integer")
     */
    private $startTime;


    /**
     * @var integer
     *
     * @Annotation\Type("integer")
     * @AppAssert\GreaterThanStartTime
     */
    private $endTime;

    /**
     * @var string
     *
     * @Annotation\Type("string")
     */
    private $status;

    /**
     * @var string
     *
     * @Annotation\Type("string")
     * @Annotation\Exclude
     */
    private $userId;

    /**
     * @var File
     * @Assert\File(
     *     maxSize = "20M",
     *     maxSizeMessage = "Sorry, maximum file size allowed is 20MB.",
     *     mimeTypes={ "video/mp4", "video/avi" }
     * )
     * @Annotation\Exclude
     */
    private $file;

    /**
     * @var string
     *
     * @Annotation\Type("string")
     * @Annotation\Exclude
     */
    private $extension;

    /**
     * @var string
     *
     * @Annotation\Type("string")
     */
    private $url;

    /**
     * Video constructor.
     * @param File $file
     */
    public function __construct(File $file = null)
    {
        $this->file = $file;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return integer
     */
    public function getStartTime()
    {
        return $this->startTime;
    }

    /**
     * @param integer $startTime
     * @return $this
     */
    public function setStartTime($startTime)
    {
        $this->startTime = $startTime;

        return $this;
    }

    /**
     * @return integer
     */
    public function getEndTime()
    {
        return $this->endTime;
    }

    /**
     * @param integer $endTime
     * @return $this
     */
    public function setEndTime($endTime)
    {
        $this->endTime = $endTime;

        return $this;
    }


    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param string $status
     * @return $this
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return string
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @param $userId
     * @return $this
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }


    /**
     * @return File
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param File $file
     * @return $this
     */
    public function setFile(File $file = null)
    {
        $this->file = $file;

        return $this;
    }

    /**
     * @return string
     */
    public function getExtension()
    {
        return $this->extension;
    }

    /**
     * @param string $extension
     * @return $this
     */
    public function setExtension($extension)
    {
        $this->extension = $extension;

        return $this;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     * @return $this
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

}

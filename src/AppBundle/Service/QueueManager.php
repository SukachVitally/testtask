<?php

namespace AppBundle\Service;

use Predis\Client;


/**
 * Class QueueManager
 */
class QueueManager
{
    const QUEUE_NAME = 'yalantis_queue';

    /**
     * @var Client
     */
    private $redis;

    /**
     * @param Client $redis
     * @return $this
     */
    public function setRedis(Client $redis)
    {
        $this->redis = $redis;

        return $this;
    }

    /**
     * @return Client
     */
    public function getRedis()
    {
        return $this->redis;
    }

    /**
     * Push item to queue.
     *
     * @param string $item
     */
    public function push($item)
    {
        $this->getRedis()->rpush(self::QUEUE_NAME, $item);
    }

    /**
     * Pop item from queue.
     *
     * @return string
     */
    public function pop()
    {
        return $this->getRedis()->rpop(self::QUEUE_NAME);
    }
}

<?php

namespace AppBundle\Command;

use AppBundle\Service\QueueManager;
use AppBundle\Service\VideoManager;


/**
 * Class VideoProcessingCommand
 */
class VideoProcessingCommand extends AbstractWorkerCommand
{
    /**
     * @var QueueManager
     */
    private $queueManager;

    /**
     * @var VideoManager
     */
    private $videoManager;

    /**
     * Config method
     */
    protected function configure()
    {
        $this
            ->setName('yalantis:video:processing')
            ->setDescription('Video processing.');
    }

    /**
     * Show worker header.
     *
     * @return string
     */
    protected function getWorkerHeader()
    {
        return "Start video processing.";
    }

    /**
     * Prepare services for process.
     */
    protected function prepareServices()
    {
        $this->queueManager = $this->getContainer()->get('app.queue_manager');
        $this->videoManager = $this->getContainer()->get('app.video_manager');
    }

    /**
     * Main process.
     */
    protected function process()
    {
        $videoId = $this->queueManager->pop();

        if (null === $videoId) {
            sleep(5);

            return;
        }

        $video = $this->videoManager->getById($videoId);
        if (null === $video) {
            return;
        }
        $this->showMessage("Get video {$video->getId()} (startTime - {$video->getStartTime()}, endTime - {$video->getEndTime()} sec)");

        $video = $this->videoManager->processingVideo($video);
        $this->showMessage("Video {$video->getId()} in status  {$video->getStatus()}");
    }
}

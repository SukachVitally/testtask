<?php

namespace AppBundle\Service;

use AppBundle\Library\DoctrineTrait;
use AppBundle\Model\Video as VideoModel;
use AppBundle\Document\Video as VideoDocument;
use AppBundle\Service\QueueManager;
use AppBundle\Utils\FfmpegFacade;
use Symfony\Component\HttpFoundation\File\File;


/**
 * Class VideoManager
 */
class VideoManager
{
    const URL_PREFIX = '/video/';
    const ORIGIN_FOLDER = 'origin';
    const PROCESSED_FOLDER = 'processed';

    use DoctrineTrait;

    /**
     * @var string
     */
    private $uploadDirectory;

    /**
     * @var QueueManager
     */
    private $queueManager;

    /**
     * @var FfmpegFacade
     */
    private $ffmpegFacade;

    /**
     * @param string $uploadDirectory
     * @return $this
     */
    public function setUploadDirectory($uploadDirectory)
    {
        $this->uploadDirectory = $uploadDirectory;

        return $this;
    }

    /**
     * @return string
     */
    public function getUploadDirectory()
    {
        return $this->uploadDirectory;
    }

    /**
     * @param QueueManager $queueManager
     * @return $this
     */
    public function setQueueManager(QueueManager $queueManager)
    {
        $this->queueManager = $queueManager;

        return $this;
    }

    /**
     * @return QueueManager
     */
    public function getQueueManager()
    {
        return $this->queueManager;
    }

    /**
     * @param FfmpegFacade $ffmpegFacade
     * @return $this
     */
    public function setFfmpegFacade(FfmpegFacade $ffmpegFacade)
    {
        $this->ffmpegFacade = $ffmpegFacade;

        return $this;
    }

    /**
     * @return FfmpegFacade
     */
    public function getFfmpegFacade()
    {
        return $this->ffmpegFacade;
    }

    /**
     * @param string $id
     * @return VideoModel
     */
    public function getById($id)
    {
        $document = $this->getDoctrine()->getRepository('AppBundle:Video')->findOneBy(['id' => $id]);

        return $this->mapDocumentToModel($document);
    }

    /**
     * Save a video model into the db storage.
     *
     * @param VideoModel $videoModel
     * @return VideoModel
     */
    public function upload(VideoModel $videoModel)
    {
        $videoExtension = $videoModel->getFile()->guessExtension();
        $videoModel->setExtension($videoExtension);
        $videoModel->setStatus(VideoModel::STATUS_SCHEDULED);
        $videoFile = $videoModel->getFile();
        $videoModel = $this->save($videoModel);

        $this->uploadFileToStorage($videoFile, $videoModel->getId());
        $this->getQueueManager()->push($videoModel->getId());

        return $videoModel;
    }

    /**
     * @param string $userId
     * @return array
     */
    public function getProcessingFiles($userId)
    {
        $videoModels = [];
        $cursor = $this->getDoctrine()->getRepository('AppBundle:Video')->findProcessedVideo($userId);
        foreach ($cursor as $videoDocument) {
            $videoModels[] = $this->mapDocumentToModel($videoDocument);
        }

        return $videoModels;
    }

    /**
     * @param string $userId
     * @return array
     */
    public function getReadyFiles($userId)
    {
        $videoModels = [];
        $cursor = $this->getDoctrine()->getRepository('AppBundle:Video')->findReadyVideo($userId);
        foreach ($cursor as $videoDocument) {
            $videoModels[] = $this->mapDocumentToModel($videoDocument);
        }

        return $videoModels;
    }

    /**
     * @param string $userId
     * @param string $videoId
     * @return bool
     */
    public function restartProcessingFile($userId, $videoId)
    {
        $result = $this->getDoctrine()->getRepository('AppBundle:Video')
            ->updateVideoStatusToScheduled($userId, $videoId);

        if ($result['nModified']) {
            $this->getQueueManager()->push($videoId);

            return true;
        }

        return false;
    }

    /**
     * @param VideoModel $video
     * @return VideoModel
     */
    public function processingVideo(VideoModel $video)
    {
        $video->setStatus(VideoModel::STATUS_PROCESSING);
        $this->save($video);
        try {
            $this->getFfmpegFacade()->cutVideo(
                $this->getUploadDirectory().'/'.self::ORIGIN_FOLDER.'/'.$video->getId().'.'.$video->getExtension(),
                $this->getUploadDirectory().'/'.self::PROCESSED_FOLDER.'/'.$video->getId().'.'.$video->getExtension(),
                $video->getStartTime(),
                $video->getEndTime()
            );
        } catch (\FFMpeg\Exception\RuntimeException $e) {
            echo $e->getMessage().'\n';
            $video->setStatus(VideoModel::STATUS_FAILED);
            return $this->save($video);
        }

        $video->setStatus(VideoModel::STATUS_DONE);
        $video->setExtension(FfmpegFacade::ENCODE_EXTENSION);
        return $this->save($video);

    }

    /**
     * @param File $file
     * @param string $fileName
     *
     * @return string
     */
    private function uploadFileToStorage(File $file, $fileName)
    {
        $extension = $file->guessExtension();
        $file->move($this->getUploadDirectory().'/'.self::ORIGIN_FOLDER, $fileName.'.'.$extension);

        return $extension;
    }

    /**
     * @param VideoDocument $videoDocument
     * @return VideoModel
     */
    private function mapDocumentToModel(VideoDocument $videoDocument)
    {
        $userModel = new VideoModel();
        $userModel->setId($videoDocument->getId());
        $userModel->setExtension($videoDocument->getExtension());
        $userModel->setStartTime($videoDocument->getStartTime());
        $userModel->setEndTime($videoDocument->getEndTime());
        $userModel->setStatus($videoDocument->getStatus());
        $userModel->setUserId($videoDocument->getUserId());
        $urlPrefix = ($videoDocument->getStatus() === VideoModel::STATUS_DONE)
            ? self::URL_PREFIX.self::PROCESSED_FOLDER.'/'
            : self::URL_PREFIX.self::ORIGIN_FOLDER.'/';
        $userModel->setUrl($urlPrefix.$videoDocument->getId().'.'.$videoDocument->getExtension());

        return $userModel;
    }

    /**
     * @param VideoModel  $videoModel
     * @return VideoDocument
     */
    private function mapModelToDocument(VideoModel $videoModel)
    {
        $videoDocument =  ($videoModel->getId())
            ? $this->getDoctrine()->getRepository('AppBundle:Video')->findOneBy(['id' => $videoModel->getId()])
            : new VideoDocument();


        $videoDocument->setExtension($videoModel->getExtension());
        $videoDocument->setUserId($videoModel->getUserId());
        $videoDocument->setStartTime($videoModel->getStartTime());
        $videoDocument->setEndTime($videoModel->getEndTime());
        $videoDocument->setStatus($videoModel->getStatus());

        return $videoDocument;
    }

    /**
     * Helper method.
     *
     * @param VideoModel $videoModel
     * @return VideoModel $videoModel
     */
    private function save(VideoModel $videoModel)
    {
        $document = $this->mapModelToDocument($videoModel);
        $em = $this->getDoctrine()->getManager();
        $em->persist($document);

        $em->flush();

        return $this->getById($document->getId());
    }

}
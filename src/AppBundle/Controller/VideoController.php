<?php

namespace AppBundle\Controller;


use AppBundle\Form\Type\UploadVideoType;
use AppBundle\Model\Video;
use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\Controller\FOSRestController;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use FOS\RestBundle\Controller\Annotations\Version;

/**
 * Rest controller for video
 *
 * @Annotations\RouteResource("/video")
 */
class VideoController extends FOSRestController
{
    /**
     * Upload new video.
     *
     * @ApiDoc(
     *   section = "Video",
     *   description = "Upload video.",
     *   input = "AppBundle\Form\Type\UploadVideoType",
     *   statusCodes = {
     *     200 = "Successful",
     *     400 = "Invalid request params",
     *     500 = "Internal server error"
     *   }
     * )
     *
     * @Annotations\Post("/video/preprocessing", name="api_post_video_preprocessing")
     *
     * @return array
     */
    public function postPreprocessingAction()
    {
        $video = new Video();
        $video->setUserId($this->getUser()->getId());
        $this->get('app.form.handler')->processForm(UploadVideoType::class, $video);

        return $this->get('app.video_manager')->upload($video);
    }

    /**
     * Show preprocessing list.
     *
     * @ApiDoc(
     *   section = "Video",
     *   description = "Show list of preprocessed video files.",
     *   statusCodes = {
     *     200 = "Successful",
     *     500 = "Internal server error"
     *   },
     * )
     *
     * @Annotations\Get("/video/preprocessing", name="api_get_video_preprocessing")
     *
     * @return array
     */
    public function getPreprocessingAction()
    {
        $videoList = $this->get('app.video_manager')->getProcessingFiles($this->getUser()->getId());

        return $videoList;
    }

    /**
     * Restart preprocessing of video file.
     *
     * @ApiDoc(
     *   section = "Video",
     *   description = "Restart video preprocessing.",
     *   requirements={
     *      {
     *          "name"="id",
     *          "dataType"="string",
     *          "description"="Preprocessing id"
     *      }
     *   },
     *   statusCodes = {
     *     200 = "Successful",
     *     500 = "Internal server error"
     *   },
     *   parameters={}
     * )
     *
     * @Annotations\Put("/video/preprocessing/{id}", name="api_put_video_preprocessing")
     *
     * @param string   $id
     * @throws NotFoundHttpException
     * @return array
     */
    public function putPreprocessingAction($id)
    {
        $result = $this->get('app.video_manager')->restartProcessingFile($this->getUser()->getId(), $id);

        if (!$result) {
            throw new NotFoundHttpException("You don't have failed video with such id.");
        }

        return [];
    }

    /**
     * Show preprocessing list.
     *
     * @ApiDoc(
     *   section = "Video",
     *   description = "Show list of ready video files.",
     *   statusCodes = {
     *     200 = "Successful",
     *     500 = "Internal server error"
     *   },
     * )
     *
     * @Annotations\Get("/video/ready", name="api_get_video_ready")
     *
     * @return array
     */
    public function getReadyAction()
    {
        $videoList = $this->get('app.video_manager')->getReadyFiles($this->getUser()->getId());

        return $videoList;
    }
}

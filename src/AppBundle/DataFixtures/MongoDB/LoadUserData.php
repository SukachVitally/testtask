<?php
namespace AppBundle\DataFixtures\MongoDB;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Document\User;
use AppBundle\Document\Video;
use AppBundle\Model\Video as VideoModel;

class LoadUserData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setUsername('testuser');
        $user->setApiKey('rightapikey');

        $manager->persist($user);
        $manager->flush();


        $videoScheduled = new Video();
        $videoScheduled->setStatus(VideoModel::STATUS_SCHEDULED);
        $videoScheduled->setStartTime(3);
        $videoScheduled->setEndTime(6);
        $videoScheduled->setExtension('mp4');
        $videoScheduled->setUserId($user->getId());

        $videoInProgress = new Video();
        $videoInProgress->setStatus(VideoModel::STATUS_PROCESSING);
        $videoInProgress->setStartTime(3);
        $videoInProgress->setEndTime(6);
        $videoInProgress->setExtension('mp4');
        $videoInProgress->setUserId($user->getId());

        $videoFailed = new Video();
        $videoFailed->setStatus(VideoModel::STATUS_FAILED);
        $videoFailed->setStartTime(3);
        $videoFailed->setEndTime(6);
        $videoFailed->setExtension('mp4');
        $videoFailed->setUserId($user->getId());


        $videoDone = new Video();
        $videoDone->setStatus(VideoModel::STATUS_DONE);
        $videoDone->setStartTime(3);
        $videoDone->setEndTime(6);
        $videoDone->setExtension('mp4');
        $videoDone->setUserId($user->getId());

        $manager->persist($videoScheduled);
        $manager->persist($videoInProgress);
        $manager->persist($videoFailed);
        $manager->persist($videoDone);

        $manager->flush();
    }
}
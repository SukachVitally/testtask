<?php

namespace Tests\AppBundle\Controller;

use AppBundle\Model\Video;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;

class VideoControllerTest extends WebTestCase
{
    public function testPostPreprocessingActionUnauthorized()
    {
        $client = static::createClient();

        $client->request('POST', '/api/video/preprocessing');

        $this->assertEquals(401, $client->getResponse()->getStatusCode());
        $this->assertTrue(
            $client->getResponse()->headers->contains('Content-Type', 'application/json')
        );
    }

    public function testPostPreprocessingEmpty()
    {
        $client = static::createClient();

        $client->request(
            'POST',
            '/api/video/preprocessing',
            [],
            [],
            ['HTTP_X-AUTH-TOKEN' => 'rightapikey']

        );

        $this->assertEquals(400, $client->getResponse()->getStatusCode());
        $this->assertTrue(
            $client->getResponse()->headers->contains('Content-Type', 'application/json')
        );
    }

    public function testPostPreprocessingWrongEndTime()
    {
        $client = static::createClient();

        $fs = new Filesystem();
        $filePath = '/tmp/testvideofile.mp4';
        $fs->copy('tests/AppBundle/Resources/Files/test.mp4', $filePath, true);

        $video = new UploadedFile(
            $filePath,
            'test.mp4',
            'video/mp4'
        );

        $client->request(
            'POST',
            '/api/video/preprocessing',
            ['start_time' => 6, 'end_time' => 5],
            ['file' => $video],
            ['HTTP_X-AUTH-TOKEN' => 'rightapikey']
        );

        $this->assertEquals(400, $client->getResponse()->getStatusCode());

        $this->assertTrue(
            $client->getResponse()->headers->contains('Content-Type', 'application/json')
        );
        $this->assertContains('End time should be more than start time', $client->getResponse()->getContent());
    }

    public function testPostPreprocessingCorrect()
    {
        $client = static::createClient();

        $fs = new Filesystem();
        $filePath = '/tmp/testvideofile.mp4';
        $fs->copy('tests/AppBundle/Resources/Files/test.mp4', $filePath, true);

        $video = new UploadedFile(
            $filePath,
            'test.mp4',
            'video/mp4'
        );

        $client->request(
            'POST',
            '/api/video/preprocessing',
            ['start_time' => 3, 'end_time' => 6],
            ['file' => $video],
            ['HTTP_X-AUTH-TOKEN' => 'rightapikey']
        );

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $this->assertTrue(
            $client->getResponse()->headers->contains('Content-Type', 'application/json')
        );
        $responseData = json_decode($client->getResponse()->getContent());
        $this->assertEquals(Video::STATUS_SCHEDULED, $responseData->status);

        return $responseData->id;
    }

    public function testGetPreprocessing()
    {
        $client = static::createClient();

        $client->request(
            'GET',
            '/api/video/preprocessing',
            [],
            [],
            ['HTTP_X-AUTH-TOKEN' => 'rightapikey']
        );

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $this->assertTrue(
            $client->getResponse()->headers->contains('Content-Type', 'application/json')
        );
        $responseData = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals(4, count($responseData));

        foreach($responseData as $item) {
            if ($item['status'] === Video::STATUS_FAILED) {
                return $item['id'];
            }
        }
    }

    public function testGetReady()
    {
        $client = static::createClient();

        $client->request(
            'GET',
            '/api/video/ready',
            [],
            [],
            ['HTTP_X-AUTH-TOKEN' => 'rightapikey']
        );

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $this->assertTrue(
            $client->getResponse()->headers->contains('Content-Type', 'application/json')
        );
        $responseData = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals(1, count($responseData));
    }

    /**
     * @depends testPostPreprocessingCorrect
     */
    public function testPutPreprocessingActionWrong($id)
    {
        $client = static::createClient();
        $client->request(
            'PUT',
            '/api/video/preprocessing/'.$id,
            [],
            [],
            ['HTTP_X-AUTH-TOKEN' => 'rightapikey']
        );

        $this->assertEquals(404, $client->getResponse()->getStatusCode());

        $this->assertTrue(
            $client->getResponse()->headers->contains('Content-Type', 'application/json')
        );
    }

    /**
     * @depends testGetPreprocessing
     */
    public function testPutPreprocessingActionCorrect($id)
    {
        $client = static::createClient();
        $client->request(
            'PUT',
            '/api/video/preprocessing/'.$id,
            [],
            [],
            ['HTTP_X-AUTH-TOKEN' => 'rightapikey']
        );

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $this->assertTrue(
            $client->getResponse()->headers->contains('Content-Type', 'application/json')
        );
    }
}

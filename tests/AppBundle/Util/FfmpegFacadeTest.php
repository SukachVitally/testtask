<?php

namespace Tests\AppBundle\Util;

use AppBundle\Utils\FfmpegFacade;

class FfmpegFacadeTest extends \PHPUnit_Framework_TestCase
{
    public function testCutVideo()
    {
        $ffmpeg = new FfmpegFacade('/usr/bin/ffmpeg', '/usr/bin/ffprobe');
        try {
            $ffmpeg->cutVideo('tests/AppBundle/Resources/Files/test.mp4', '/tmp/testvideofile.mp4', 3, 6);
        } catch (\FFMpeg\Exception\RuntimeException $e) {
            $this->assertFalse(true);
        }
    }
}
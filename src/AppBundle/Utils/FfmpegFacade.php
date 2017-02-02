<?php

namespace AppBundle\Utils;

use FFMpeg\FFMpeg;
use FFMpeg\Coordinate\TimeCode;
use FFMpeg\Format\Video\X264;


/**
 * Class FfmpegFacade
 */
class FfmpegFacade
{
    const ENCODE_EXTENSION = 'mp4';

    /**
     * @var FFMpeg
     */
    private $ffmpeg;

    /**
     * FfmpegFacade constructor.
     */
    public function __construct($ffmpegPath, $ffprobePath)
    {
        $this->ffmpeg = FFMpeg::create([
            'ffmpeg.binaries'  => $ffmpegPath,
            'ffprobe.binaries' => $ffprobePath,
            'timeout'          => 0,
        ]);
    }

    public function cutVideo($inputFile, $outputFile, $startTime, $endTime)
    {
        $video = $this->ffmpeg->open($inputFile);

        $video->filters()->clip(TimeCode::fromSeconds($startTime), TimeCode::fromSeconds($endTime - $startTime));
        $video->save(new X264('libmp3lame', 'libx264'), $outputFile);
    }

}

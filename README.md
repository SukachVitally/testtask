# Yalantis test task
## Install

1) composer install
2) install ffmpeg lib
3) require PHP, mongoDB (ass base storage), Redis (for queue)


## API
Application already have documentation /api/doc (swagger v1.2)
Support version API in headers version=1.0

1) Create user and get unique apiKey. Only this endpoint is open for unauthorized users.

*Has validation on empty field*

Request

    curl -X "POST" -d "username=username" -H "Accept:\ application/json;version=1.0" -H "Content-type:\ application/json" /api/users

Response

    Success - 200
    {
      "id": "589303fc9a8920064030bb22",
      "username": "sdfgsdfg",
      "apikey": "3be3683464bd817ae4f6ec474e3c18d16fe1d23421fda0939b23a512bae33ae9"
    }
    Error - 400
    {
      "code": 400,
      "message": "You must send all the field!"
    }

2) Upload video for processing

*Has validation on empty fields and start time should be less than end time*

Request

    curl -X "POST" -d "file=test.mp4&start_time=3&end_time=6" -H "X-AUTH-TOKEN:\ eb6d113c0deb4472aef1d4b89952f6a1bad0939c8db60dd581ed6114fc53c88e" /api/video/preprocessing

Response

    Success - 200
    {
      "id": "589306499a8920064030bb24",
      "starttime": 3,
      "endtime": 6,
      "status": "scheduled",
      "url": "/video/origin/589306499a8920064030bb24.mp4"
    }

3) Show all video in preprocessing (processing, scheduled, failed)

Request

    curl -X "GET" -H "Accept:\ application/json;version=1.0" -H "X-AUTH-TOKEN:\ eb6d113c0deb4472aef1d4b89952f6a1bad0939c8db60dd581ed6114fc53c88e" /api/video/preprocessing

Response

    [
      {
        "id": "5891e53f9a892061ad0ef6f3",
        "starttime": 3,
        "endtime": 6,
        "status": "scheduled",
        "url": "/video/origin/5891e53f9a892061ad0ef6f3.mp4"
      },
      {
        "id": "5891e5499a892061ac0294c4",
        "starttime": 3,
        "endtime": 6,
        "status": "scheduled",
        "url": "/video/origin/5891e5499a892061ac0294c4.mp4"
      }
    ]

4) Restart failed video

Request

    curl -X "PUT" -H "Accept:\ application/json;version=1.0" -H "X-AUTH-TOKEN:\ eb6d113c0deb4472aef1d4b89952f6a1bad0939c8db60dd581ed6114fc53c88e" /api/video/preprocessing/589306499a8920064030bb24

Response

    Success - 200
    []

    If video doesn't exist of not in status
    {
      "code": 404,
      "message": "You don't have failed video with such id."
    }


## Background process

Start command in console for processing video

    php bin/console yalantis:video:processing

Output example

    Start video processing.
    [2017-02-02 10:13:32] Get video 589306499a8920064030bb24 (startTime - 3, endTime - 6 sec)
    [2017-02-02 10:13:43] Video 589306499a8920064030bb24 in status  done

## Test

Application has functional tests

    start phpunit in root project folder


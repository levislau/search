<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/10 0010
 * Time: 下午 6:44
 */
namespace Search\Video;

class VideoFactory
{

    public $videoObj;

    public function __construct($videoName)
    {
        $videoName = ucfirst($videoName);

        $this->videoObj = new $videoName();
    }
}
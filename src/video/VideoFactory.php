<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/10 0010
 * Time: 下午 6:44
 */
namespace Search\Video;

require_once 'Iqiyi.php';
require_once 'Youku.php';
require_once 'Vqq.php';

class VideoFactory
{

    public $videoObj;

    public function __construct($videoName)
    {
        $videoName = ucfirst($videoName);

        $this->videoObj = new $videoName();
    }
}
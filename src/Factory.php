<?php
/**
 * User: Administrator
 * Date: 2017/5/11 0011
 * Time: 下午 4:12
 */
namespace XuLiangLiang\Search;

require_once 'video/Iqiyi.php';
require_once 'video/Youku.php';
require_once 'video/Vqq.php';

require_once 'pic/Picdown.php';
require_once 'pic/Baidupic.php';
require_once 'pic/Sansixpic.php';

class Factory
{

    public $factoryObj;

    public function getSelfObj($name)
    {
        $objName = ucfirst($name);
        $this->factoryObj = new $objName;
    }
}
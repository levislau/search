# search
this is gather iqiyi youku vqq video urls

# demo
include 'vendor/autoload.php';

use Search\Factory;

//$video_type ['iqiyi','youku','vqq'],只选择其中一个作为参数即可

$vfactory = new Factory($video_type);

$videoObj = $vfactory->videoObj;

//获得列表页的数组['title'=>'标题','litpic'=>'缩略图','con_url'=>'内容链接地址']

//$keyword (搜索的关词) ,$pagenum (第几页)

$data = $videoObj->getVideoList($keyword,$pagenum);

if($data === null){
    echo "this is error";
    exit;
}

//获得内容视频链接,$con_url上面的数据中的con_url

$videoUrl = $videoObj->getVideoBody($con_url);

if($videoUrl === null){
    echo "this is error";
    exit;
}

# demo with pic
# baidu
//size [全部尺寸=>0,特大尺寸=>9,大尺寸=>3,中尺寸=>2,小尺寸=>1];

//如果传递了with 或 height 就不需要传递size

//mold = ['全部'=>'s=0&lm=0&st=-1&face=0','头像'=>'s=3&lm=0&st=-1&face=0','面部特写'=>'s=0&lm=0&st=-1&face=1','卡通漫画'=>'s=0&lm=0&st=1&face=0','简笔画'=>'s=0&lm=0&st=2&face=0','动态图片=>'s=0&lm=6&st=-1&face=0','静态图片'=>'s=0&lm=7&st=-1&face=0']

//color ['all'=>0,'red'=>1,'orage'=>256,'yellow'=>2,'green'=>4,'violet'=>32,'pink'=>64,'cyan'=>8,'blue'=>16,'brown'=>128,'white'=>1024,'black'=>512,'black-and-white'=>2048];

//return picurlsArr

include "vendor/autoload.php"

use Search\Factory

//$keyword 

$width = 800;

$height = 800;

$size = 0|9...

$mold = 's=0&lm=0&st=-1&face=0'

$color = 0

$attrArr = array(
//    'width' => $width,
//    'height' => $height,
    'size'=>$size,
    'mold' => $mold,
    'color' => $color
);

$pfactory = new Factory('baidupic');

$baidupicObj = $pfactory->factoryObj;

$rest = $baiduPicObj->getPic($keyword, $attrArr);


# 360
// retrun pic urls arr

//size [全部尺寸=>0,大尺寸=>1,中尺寸=>2,小尺寸=>3,壁纸尺寸=>4];
//mold  [动态图片=>d 静态图片=>s]
//&color [red orange yellow green grass blue purple pink brown white black bandw]

include "vendor/autoload.php"

use Search\Factory

$pfactory = new Factory('sansixpic')

$sansixpicObj = $pfactory->factoryObj;

$rest = $sansixpicObj->getpic($key,$attrArr);









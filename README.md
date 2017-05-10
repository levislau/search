# search
this is gather iqiyi youku vqq video urls

#demo
include 'vendor/autoload.php';

use Search\Video\VideoFactory;

//$video_type ['iqiyi','youku','vqq']

$vfactory = new VideoFactory($video_type);

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












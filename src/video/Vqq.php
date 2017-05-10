<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/10 0010
 * Time: 下午 6:47
 */
require_once 'VideoFace.php';
use QL\QueryList;

class Vqq implements \VideoSearch
{
    /**
     * @param $keyWord
     * @param $page
     * @return null
     */
    public function getVideoList($keyWord,$page)
    {
        $data = null;

        $keyWord = urlencode($keyWord);
        $s_url = 'https://v.qq.com/x/search/?q=' . $keyWord . '&cur=' . $page;

        $data = QueryList::Query($s_url, array(
            'title' => array('.result_title', 'text'),
            'litpic' => array('a > img', 'src'),
            'con_url' => array('a', 'href'),
        ), '.result_item_h')->getData(function ($item) {
            $item['litpic'] = strtr($item['litpic'], array('//' => 'http://'));
            return $item;
        });
        return $data;
    }


    /**
     * @param $conUrl
     * @return null|string
     */
    public function getVideoBody($conUrl)
    {
        $body = null;
        $url_str = parse_url($conUrl);

        if (isset($url_str['scheme']) === false || in_array($url_str['scheme'], array('http', 'https')) === false || $url_str['host'] !== 'v.qq.com') {
            return $body;
        }

        $path_info = pathinfo($url_str['path']);
        if(isset($path_info['filename']) === false || empty($path_info['filename']) === true){
            return $body;
        }

        //XNjczNjE4MjI0
        $body = 'https://v.qq.com/iframe/player.html?vid='.$path_info['filename'].'&tiny=0&auto=0';
        return $body;
    }
}

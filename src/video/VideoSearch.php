<?php
/**
 * Date: 2017/5/9 0009
 * Time: 下午 9:18
 */

use QL\QueryList;

interface VideoSearch
{
    public function getVideoList($keyWord,$page);
    public function getVideoBody($conUrl);
}

class Iqiyi implements VideoSearch
{
    /**
     * 获得爱奇艺视频的列表页链接
     */
    public function getVideoList($keyWord, $page)
    {
        $data = null;

        $p_keywords = urlencode($keyWord);
        $s_url = 'http://so.iqiyi.com/so/q_' . $p_keywords . '_page_' . $page;

        //这只是第一页的内容
        $data = QueryList::Query($s_url, array(
            'title' => array('.result_title', 'text'),
            'litpic' => array('a:first img', 'src'),
            'con_url' => array('a:first', 'href'),
        ), '.mod_result_list .list_item')->getData(function ($item) {
//                    $item['litpic'] = strtr($item['litpic'], array('//' => 'http://'));
            $item['title'] = strtr($item['title'], array(' ' => '', "\r" => '', "\n" => '', "\t" => ''));
            return $item;
        });

        return $data;
    }


    /**
     * 得到爱奇艺视频播放链接
     * //对错误的处理
     */
    public function getVideoBody($con_url)
    {

        $body = null;

        $url_str = parse_url($con_url);

        if (isset($url_str['scheme']) === false || in_array($url_str['scheme'], array('http', 'https')) === false || empty($url_str['path']) === true) {
            return $body;
        }
        $path_info = pathinfo($url_str['path']);
        if (isset($path_info['filename']) === false || empty($path_info['filename']) === true || (stripos($path_info['filename'], 'w_') !== 0 && stripos($path_info['filename'], 'v_') !== 0)) {
            return $body;
        }

        //这个是具体的内容
        $data = QueryList::Query($con_url, array())->getHtml();
        preg_match('/param\[\'tvid\'\]\s*=\s*"(\d+?)";/is', $data, $tvids);
        preg_match('/param\[\'vid\'\]\s*=\s*"(.+?)";/is', $data, $vids);
        $tvid = $tvids[1];
        $vid = $vids[1];

        $body = 'http://open.iqiyi.com/developer/player_js/coopPlayerIndex.html?vid=' . $vid . '&tvId=' . $tvid;

        return $body;
    }
}

class Youku implements VideoSearch
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
        $page_url = 'http://www.soku.com/search_video/q_' . $keyWord . '_orderby_1_limitdate_0?site=14&page=' . $page . '&spm=a2h0k.8191407.0.0';
        $data = QueryList::Query($page_url, array(
            'title' => array('.v-meta-title', 'text'),
            'litpic' => array('.v-thumb img', 'src'),
            'con_url' => array('.v-link a', 'href'),
        ), '.v')->getData(function ($item) {
            $item['title'] = strtr($item['title'], array(' ' => '', "\r" => '', "\n" => '', "\t" => ''));
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

        if (isset($url_str['scheme']) === false || in_array($url_str['scheme'], array('http', 'https')) === false || $url_str['host'] !== 'v.youku.com') {
            return $body;
        }

        if (preg_match('/id_(.*(==)?)\.html/is', $conUrl, $match) === 0) {
            return $body;
        }

        $vid = $match[1];
        //XNjczNjE4MjI0
        $body = 'http://player.youku.com/embed/' . $vid;

        return $body;
    }
}


class Vqq implements VideoSearch
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


class VideoFactory
{

    public $videoObj;

    public function __construct($videoName)
    {
        $videoName = ucfirst($videoName);

        $this->videoObj = new $videoName();
    }
}























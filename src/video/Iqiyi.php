<?php
/**
 * User: Administrator
 * Date: 2017/5/10 0010
 * Time: 下午 6:46
 */

require_once 'VideoFace.php';
use QL\QueryList;

class Iqiyi implements \VideoSearch
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

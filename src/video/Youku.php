<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/10 0010
 * Time: 下午 6:47
 */

require_once 'VideoFace.php';
use QL\QueryList;

class Youku implements \VideoSearch
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





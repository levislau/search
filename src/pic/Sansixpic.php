<?php
/**
 * User: Administrator
 * Date: 2017/5/11 0011
 * Time: 下午 4:57
 */
use QL\QueryList;

class Sansixpic
{

    private $width;
    private $height;
    private $size;
    private $mold;
    private $color;

    public function getPic($keyword, $attr = null)
    {
        $pic = null;
        $keyCon = null;

        if ($attr !== null) {
            if (is_array($attr) === false) {
                $pic = array(
                    'code' => 'error',
                    'info' => 'attr is not a empty'
                );
                return $pic;
            }

            if (isset($attr['size']) === true && (isset($attr['width']) === true || isset($attr['height']) === true)) {
                $pic = array(
                    'code' => 'error',
                    'info' => 'attr is not include size and width or height',
                );
                return $pic;
            }
        }

        $this->init();

        foreach ($attr as $key => $value) {
            $key = strtolower(trim($key));
            if ($key === 'size') {
                $keyCon = 'zoom';
            } elseif ($key === 'mold') {
                $keyCon = 't';
            }
            $this->$key = '&' . $keyCon . '=' . $value;
        }

        $keyword = urlencode($keyword);
        $url = 'http://image.so.com/i?q=' . $keyword . '&src=srp'.$this->width.$this->height.$this->size.$this->mold.$this->color;

        $html = QueryList::Query($url, array())->getHtml();

        $pic = $this->getPicUrl($html);
        return $pic;
    }


    private function init()
    {
        $this->width = '';
        $this->height = '';
        $this->size = '';
        $this->mold = '';
        $this->color = '';
    }


    /**
     * 正则解析得到图片链接
     * @param $html
     */
    private function getPicUrl($html)
    {
        $picUrlArr = null;

        preg_match('/<script type="text\/data" id="initData">(.*?)<\/script>/i',$html,$arr);
        $str = json_decode($arr[1], true);
        foreach ($str['list'] as $value) {
            $picUrlArr[] = $value['img'];
        }

        return $picUrlArr;
    }
}

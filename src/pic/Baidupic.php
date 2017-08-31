<?php
/**
 * User: Administrator
 * Date: 2017/5/11 0011
 * Time: 下午 4:11
 */

use QL\QueryList;

class Baidupic
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

        if($attr !== null) {
            foreach ($attr as $key => $value) {
                $keyCon = strtolower(trim($key));

                if ($key === 'size') {
                    $keyCon = 'z';
                } elseif ($key === 'mold') {
                    $keyCon = '';
                } elseif ($key === 'color') {
                    $keyCon = 'ic';
                }
                $this->$key = '&' . $keyCon . '=' . $value;
            }
        }

        $keyword = urlencode($keyword);
        $url = 'https://image.baidu.com/search/index?tn=baiduimage&word=' . $keyword . '&pn=0&istype=2&ie=utf-8&oe=utf-8&fr=&se=&sme=' . $this->width . $this->height . $this->size . $this->mold . $this->color;

        $html = QueryList::Query($url, array())->getHtml();

        $pic = $this->getPicUrl($html);
        return $pic;
    }


    private function init()
    {
        $this->width = '&width=0';
        $this->height = '&height=0';
        $this->size = '&z=0';
        $this->mold = 's=0&lm=0&st=-1&face=0';
        $this->color = '&ic=0';
    }


    /**
     * 正则解析得到图片链接
     * @param $html
     */
    private function getPicUrl($html)
    {
        $picUrlArr = null;
        preg_match("/imgs:\[(.*)headPic:\{/Us", $html, $arr);
        if (empty($arr[1])) {
            preg_match("/\"data\":(\[.*\{\}\])/Us", $html, $arr2);
            if (empty($arr2[1])) {
                $picUrlArr = array(
                    'code' => 'error',
                    'info' => 'no pic urls'
                );
                return $picUrlArr;
            }
            $str = $arr2[1];
            $str = str_replace(array('data:img/jpeg;base64,', '\''), array('', '"'), $str);
            $str = json_decode($str, true);
        } else {
            $str = $arr[1];
            $str = trim($str);
            $str = trim($str, ' ],');
            $str = trim($str);
            $str = str_replace(array('//0,', '// 0,'), array('', ''), $str);
            $str = '[' . $str . ']';
            $str = json_decode($str, true);
        }

        if (empty($str) === true) {
            $picUrlArr = array(
                'code' => 'error',
                'info' => 'no pic urls',
            );
            return $picUrlArr;
        }
        foreach ($str as $key => $value) {
            if(isset($value['objURL']) === true){
                $picUrlArr[] = $value['objURL'];
            }
        }
        return $picUrlArr;
    }
}
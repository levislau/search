<?php
/**
 * User: Administrator
 * Date: 2017/5/11 0011
 * Time: 下午 4:16
 */
class Picdown
{

    /**
     * @param $savePath 保存路径
     * @param $url 可以是数组或者不是数组,如果url为数组，则保存路径也传进来一个数组
     */
    public function imgDown($savePath,$url)
    {
        $rest = array();
        $fp = null;
        $ch = null;

        if(is_array($url) === true){
            //多线程
            $mch = curl_multi_init();

            foreach ($url as $key => $value) {
                $fp[$key] = fopen($savePath[$key], 'wb');
                $ch[$key] = $this->getCurlObject($value, $fp[$key]);
                curl_multi_add_handle($mch, $ch[$key]);
            }

            $active = null;
            // 执行批处理句柄
            do {
                $mrc = curl_multi_exec($mch, $active);
            } while ($mrc == CURLM_CALL_MULTI_PERFORM);

            while ($active && $mrc == CURLM_OK) {
                // add this line
                while (curl_multi_exec($mch, $active) === CURLM_CALL_MULTI_PERFORM) ;

                if (curl_multi_select($mch) != -1) {
                    do {
                        $mrc = curl_multi_exec($mch, $active);
                    } while ($mrc == CURLM_CALL_MULTI_PERFORM);
                }
            }

            //释放线程保存文件
            foreach ($ch as $key => $item) {
//                   $info = curl_multi_getcontent($item);
                $info = curl_getinfo($item);
                fclose($fp[$key]);
                curl_multi_remove_handle($mch, $item);
                curl_close($item);
                if($info['http_code'] == '200'){
                    $rest[] = $savePath[$key];
                }else{
                    $rest[] = $savePath['$key'].' download error!!';
                }
            }
            curl_multi_close($mch);

        }else{
            //单线程
            $fp = fopen($savePath,'wb');
            $ch = $this->getCurlObject($url,$fp);
            curl_exec($ch);
            $info = curl_getinfo($ch);
            fclose($fp);
            curl_close($ch);
            if($info['http_code'] == '200'){
                $rest[] = $savePath;
            }else{
                $rest[] = $savePath.' download error!!';
            }
        }
        return $rest;
    }


    /**
     * 多线程
     */
    private function getCurlObject($url, $fp, $postdata = array(), $header = array())
    {
        $host = parse_url($url,PHP_URL_HOST);
        $option = array();
        $url = trim($url);
        $option[CURLOPT_URL] = $url;
        $option[CURLOPT_TIMEOUT] = 10;
        $option[CURLOPT_RETURNTRANSFER] = true;
        $option[CURLOPT_HEADER] = false;
        $option[CURLOPT_USERAGENT] = 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/53.0.2763.0 Safari/537.36';
        $option[CURLOPT_NOBODY] = false;
        $option[CURLOPT_FILE] = $fp;
        $option[CURLOPT_HTTPHEADER] = array('Host:'.$host);


        if (empty($postdata) === false && is_array($postdata) === true) {
            $option[CURLOPT_POST] = true;
            $option[CURLOPT_POSTFIELDS] = http_build_query($postdata);
        }

        if (empty($header) === false && is_array($header) === true) {
            foreach ($header as $header_key => $header_value) {
                $option[$header_key] = $header_value;
            }
        }

        if (stripos($url, 'https') === 0) {
            $option[CURLOPT_SSL_VERIFYHOST] = 2; //(1|2)
            $option[CURLOPT_SSL_VERIFYPEER] = 0;
        }

        $ch = curl_init();
        curl_setopt_array($ch, $option);
        return $ch;
    }

}
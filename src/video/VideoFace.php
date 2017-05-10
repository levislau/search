<?php
/**
 * User: Administrator
 * Date: 2017/5/10 0010
 * Time: 下午 6:46
 */
interface VideoSearch
{
    public function getVideoList($keyWord,$page);
    public function getVideoBody($conUrl);
}
<?php
require "UrlRepository.php";
include "../connection/redis.php";

class UrlRepositoryImpl implements UrlRepository
{
    public function getUrlObjectByUser($userid)
    {
        $urlList = array();
        $r = $GLOBALS['redis'];
        $keys = $r->keys("*");
        foreach ($keys as $key) {
            if($r->hGet($key, 'userid') == $userid) {
                $url = array(
                    "userid"=>$r->hGet($key, 'userid'),
                    "timestamp"=>$r->hGet($key, 'timestamp'),
                    "clicked"=>$r->hGet($key, 'clicked'),
                    "redirectURL"=>$r->hGet($key, 'url'),
                    "title"=>$r->hGet($key, 'title'),
                    "ip"=>$r->hGet($key, 'ip'),
                    "status"=>$r->hGet($key, 'status'));
                $urlList[$key] = $url;
            }
        }

        return $urlList;
    }

    public function getUrlObjectByCode($code)
    {
        // TODO: Implement getUrlObjectByCode() method.
    }
}
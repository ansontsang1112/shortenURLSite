<?php
require "UrlRepository.php";
include "../connection/redis.php";

class UrlRepositoryImpl implements UrlRepository
{
    public function getUrlObjectByUser($userid): array
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

    public function getUrlObjectByCode($code): ?array
    {
        // TODO: Implement getUrlObjectByCode() method.

        $r = $GLOBALS['redis'];
        $keys = $r->keys("*");
        $url = array();
        foreach ($keys as $key) {
            if($key == $code) {
                $url = array(
                    "userid"=>$r->hGet($key, 'userid'),
                    "timestamp"=>$r->hGet($key, 'timestamp'),
                    "clicked"=>$r->hGet($key, 'clicked'),
                    "redirectURL"=>$r->hGet($key, 'url'),
                    "title"=>$r->hGet($key, 'title'),
                    "ip"=>$r->hGet($key, 'ip'),
                    "status"=>$r->hGet($key, 'status'));
            }
        }

        return (empty($url)) ? null : $url;
    }

    public function removeUrlByCode($code) : bool
    {
        // TODO: Implement removeUrlByCode() method.
        $r = $GLOBALS['redis'];
        $urlChecked = $this->getUrlObjectByCode($code);

        if($urlChecked != null) {
            $r->delete($code);
            return true;
        } else {
            return false;
        }
    }

    public function replaceUrlOwner($oldOwner, $newOwner)
    {
        // TODO: Implement replaceUrlOwner() method.
        $url_list = $this->getUrlObjectByUser($oldOwner);
        foreach ($url_list as $key => $value) {
            $GLOBALS['redis']->hSet($key, 'userid', $newOwner);
        }
    }
}
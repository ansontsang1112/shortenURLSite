<?php
include "../connection/redis.php";

function code62($x)
{
    $show = '';
    while ($x > 0) {
        $s = $x % 62;
        if ($s > 35) {
            $s = chr($s + 61);
        } elseif ($s > 9 && $s <= 35) {
            $s = chr($s + 55);
        }
        $show .= $s;
        $x = floor($x / 62);
    }
    return $show;
}

function shorturl($url)
{
    $url = crc32($url);
    $result = sprintf("%u", $url);
    return code62($result);
}

function isRepeated($code) {
    foreach($GLOBALS['redis']->keys("*") as $key) {
        if(shorturl($code) == $key) {
            return true;
        }
    }
    return false;
}

function getKeyInfo($code) {
    $informational = array();
    foreach($GLOBALS['redis']->keys("*") as $key) {
        if($code == $key) {
            $i = $GLOBALS['redis'];
            $informational['code'] = $key;
            $informational['url'] = $i->hGet($key, 'url');
            
            return $informational;
        }
    }
    return false;
}

function getShortenURL($key) {
    if($GLOBALS['redis']->get($key) != null) {
        return $GLOBALS['redis']->get($key);
    } else{
        return "Not Found";
    }
}

function getUserIP() {
	if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
			  $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
			  $_SERVER['HTTP_CLIENT_IP'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
	}
	$client  = @$_SERVER['HTTP_CLIENT_IP'];
	$forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
	$remote  = $_SERVER['REMOTE_ADDR'];

	if(filter_var($client, FILTER_VALIDATE_IP)) { $ip = $client; }
	elseif(filter_var($forward, FILTER_VALIDATE_IP)) { $ip = $forward; }
	else { $ip = $remote; }

	return $ip;
}

function getTitle($url) {
    if (!function_exists('curl_init'))
    {
        die('CURL is not installed!');
    }
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $output = curl_exec($ch);

    // get the code of request
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    // FAIL
    if ($httpCode == 400) return "Fail";

    // SUCCEED!
    if ($httpCode == 200)
    {
        $str = file_get_contents($url);
        if (strlen($str) > 0)
        {
            $str = trim(preg_replace('/\s+/', ' ', $str)); // supports line breaks inside <title>
            preg_match("/\<title\>(.*)\<\/title\>/i", $str, $title); // ignore case
            return $title[1];
        }
    }
}
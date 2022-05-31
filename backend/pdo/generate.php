<?php
include "fn.php";
include "../connection/mysql.php";
include "../dao/UserRepositoryImpl.php";

date_default_timezone_set("Asia/Hong_Kong");
session_start();

// Variable
$impl = new UserRepositoryImpl();

$url = $_POST['url'];
$default_click = 0;

if($_SESSION['isLogin']) {
    switch ($_SESSION['method']) {
        case "discord":
            $user = $impl->getUserProfileByDiscordID($_SESSION['userObject']->id);
            break;
        case "member":
            $user = unserialize($_SESSION['userObject']);
            break;
    }
}

$userid = ($_SESSION['isLogin']) ? $user->getUid() : "anonymous";
$timestamp = time();
$ip = $_SESSION['clientIP'];
$title = (getTitle($url) != "") ? getTitle($url) : "Title not fetch";

// Check if URL is null
if($url == null) {
    header("Location: ../index.php?err=null_url");
}

if($title == "Fail") {
    header("Location: ../index.php?err=url_fetch_error");
}

// Check if repeated
$key = shorturl($url);

// If key exist
if($GLOBALS['redis']->exists($key)) {
    $key = generateRandomString();
}

$value_array = array("userid" => $userid, "ip" => $ip, "clicks" => $default_click, "timestamp" => $timestamp, "title" => $title, "url" => $url, "status" => "active");

// Redis Operation
$GLOBALS['redis']->hMset($key, $value_array);

header("Location: ../../index.php?s=" . $key);
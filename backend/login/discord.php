<?php
include_once "../connection/mysql.php";
include_once "../dao/UserRepositoryImpl.php";
include_once "../dao/UrlRepositoryImpl.php";
date_default_timezone_set("Asia/Hong_Kong");

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
ini_set('max_execution_time', 300);
error_reporting(E_ALL);

const OAUTH2_CLIENT_ID = '977981235618021377';
const OAUTH2_CLIENT_SECRET = 'wFQjXV7UQqS4Jx55UFhDVfRuv169Uu4j';

$authorizeURL = 'https://discord.com/api/oauth2/authorize';
$tokenURL = 'https://discord.com/api/oauth2/token';
$apiURLBase = 'https://discord.com/api/users/@me';
$revokeURL = 'https://discordapp.com/api/oauth2/token/revoke';

$redirect_uri = "https://dev.hypernology.com/projects/current_projects/shortenURL/backend/login/discord.php";

session_start();

if ($_GET['action'] == 'login') {
  $params = array(
    'client_id' => OAUTH2_CLIENT_ID,
    'redirect_uri' => $redirect_uri,
    'response_type' => 'code',
    'scope' => 'identify email guilds'
  );
  header('Location: https://discord.com/api/oauth2/authorize' . '?' . http_build_query($params));
  die();
}

if (get('code')) {
  $token = apiRequest($tokenURL, array(
    "grant_type" => "authorization_code",
    'client_id' => OAUTH2_CLIENT_ID,
    'client_secret' => OAUTH2_CLIENT_SECRET,
    'redirect_uri' =>  $redirect_uri,
    'code' => get('code')
  ));
  $logout_token = $token->access_token;
  $_SESSION['access_token'] = $token->access_token;
  header('Location: ' . $_SERVER['PHP_SELF']);
}

if (session('access_token')) {
  $user = apiRequest($apiURLBase);
  $_SESSION['userObject'] = $user;
  $_SESSION['method'] = "discord";
  $_SESSION['isLogin'] = true;

  $impl = new UserRepositoryImpl();
  $urlImpl = new UrlRepositoryImpl();
  // Check if user in db
    if(!$impl->isUserExistByDiscordID($user->id)) {
        // Add User to user_db
        $userObject = new User($user->username, $user->email, time(), 'Active', false);
        $userObject->setDiscordID($user->id);
        $uid = $userObject->getUid();
        $_SESSION['uid'] = $impl->createUser($userObject);
    } else {
        $userObj = $impl->getUserProfileByDiscordID($user->id);
        $uid = $userObj->getUid();
        $_SESSION['uid'] = $userObj->getUid();
    }

    $_SESSION['url_list'] = $urlImpl->getUrlObjectByUser($uid);

  header("Location: ../../index.php");
} else {
  header('Location: ' . $_SERVER['PHP_SELF'] . '?action=login');
}

if (get('action') == 'logout') {
  logout($revokeURL, array(
    'token' => session('access_token'),
    'token_type_hint' => 'access_token',
    'client_id' => OAUTH2_CLIENT_ID,
    'client_secret' => OAUTH2_CLIENT_SECRET,
  ));
  unset($_SESSION['access_token']);
  unset($_SESSION['method']);
  unset($_SESSION['userObject']);
  $_SESSION['isLogin'] = false;

  header('Location: ../../index.php?logout');
  die();
}

function apiRequest($url, $post = FALSE, $headers = array())
{
  $ch = curl_init($url);
  curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
  $response = curl_exec($ch);
  if ($post) {
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));
  }
  $headers[] = 'Accept: application/json';
  if (session('access_token'))
    $headers[] = 'Authorization: Bearer ' . session('access_token');
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  $response = curl_exec($ch);
  return json_decode($response);
}

function logout($url, $data = array())
{
  $ch = curl_init($url);
  curl_setopt_array($ch, array(
    CURLOPT_POST => TRUE,
    CURLOPT_RETURNTRANSFER => TRUE,
    CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4,
    CURLOPT_HTTPHEADER => array('Content-Type: application/x-www-form-urlencoded'),
    CURLOPT_POSTFIELDS => http_build_query($data),
  ));
  $response = curl_exec($ch);
  return json_decode($response);
}

function get($key, $default = NULL)
{
  return array_key_exists($key, $_GET) ? $_GET[$key] : $default;
}

function session($key, $default = NULL)
{
  return array_key_exists($key, $_SESSION) ? $_SESSION[$key] : $default;
}

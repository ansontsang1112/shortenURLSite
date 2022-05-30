<?php
include_once "../connection/mysql.php";
include_once "../dao/UserRepositoryImpl.php";
include_once "../dao/UrlRepositoryImpl.php";
date_default_timezone_set("Asia/Hong_Kong");
session_start();

if($_GET['action'] == "login") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $url = "";

    if($username != null && $password != null) {
        $user_profile = $GLOBALS['ms']->query("SELECT * FROM member_records WHERE username = '$username'");
        if($user_profile->num_rows > 0) {
            $result = $user_profile->fetch_assoc();
            $md5Pwd = md5($password);

            if($md5Pwd != $result['auth_pwd']) {
                $url = "../../index.php?login_err=fail_login";
            }

            // Success Logic
            $userImpl = new UserRepositoryImpl();
            $urlImpl = new UrlRepositoryImpl();

            if(!$userImpl->isUserExistByMemberID($result['member_id'])) {
                $user = new User($result['username'], $result['email'], time(), 'Active', false);
                $user->setMemberID($result['member_id']);

                $uid = $user->getUid();
                $_SESSION['userObject'] = serialize($user);
                $_SESSION['uid'] = $userImpl->createUser($user);
            } else {
                $userObj = $userImpl->getUserProfileByMember($result['member_id']);
                $uid = $userObj->getUid();

                $_SESSION['userObject'] = serialize($userObj);
                $_SESSION['uid'] = $userObj->getUid();
            }

            $_SESSION['url_list'] = $urlImpl->getUrlObjectByUser($uid);

            var_dump($_SESSION['userObject']);

            $_SESSION['method'] = "member";
            $_SESSION['isLogin'] = true;

            $url = "../../index.php";
        } else {
            $url = "../../index.php?login_err=fail_login";
        }
    } else {
        $url = "../../index.php?login_err=fail";
    }

    //header("Location: " . $url);
}

if($_GET['action'] == "logout") {
    unset($_SESSION['method']);
    unset($_SESSION['userObject']);
    unset($_SESSION['uid']);
    unset($_SESSION['url_list']);
    $_SESSION['isLogin'] = false;

    session_destroy();
    header('Location: ../../index.php?logout');
    die();
}

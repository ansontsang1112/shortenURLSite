<?php
include "fn.php";
require "../connection/mysql.php";
include "../dao/UserRepositoryImpl.php";
include "../dao/UrlRepositoryImpl.php";

session_start();

$url = "";
$userImpl = new UserRepositoryImpl();
$urlImpl = new UrlRepositoryImpl();

if(isset($_GET['migrate']) && isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username']; $password = $_POST['password'];
    $uid = $_SESSION['uid'];

    if($GLOBALS["ms"]->query("SELECT * FROM member_records WHERE username = '$username'")->num_rows <= 0) {
        header("Location: ../../migrate.php?login=user_not_found");
    } else {
        $umsQuery = $GLOBALS["ms"]->query("SELECT * FROM member_records WHERE username = '$username'")->fetch_assoc();

        if(md5($password) != $umsQuery['auth_pwd']) {
            header("Location: ../../migrate.php?login=user_or_password_fail");
        } else {

            // Migration
            $memberId = $umsQuery['member_id'];
            if($GLOBALS['udb']->query("SELECT * FROM user_info WHERE member_id = '$memberId'")->num_rows <= 0) {
                $GLOBALS['udb']->query("UPDATE user_info SET member_id = '$memberId' WHERE uid = '$uid'");
            } else {
                // Get the UID of member account
                $memberAccountUid = $GLOBALS['udb']->query("SELECT * FROM user_info WHERE member_id = '$memberId'")->fetch_assoc()['uid'];

                // Replace All URL from redis db
                $urlImpl->replaceUrlOwner($memberAccountUid, $uid);

                // Update Account
                $GLOBALS['udb']->query("UPDATE user_info SET member_id = '$memberId' WHERE uid = '$uid'");

                // Remove Account
                $GLOBALS['udb']->query("DELETE FROM user_info WHERE uid = '$memberAccountUid'");

            }

            $_SESSION['isLinkedMember'] = true;
            $url = "../../migrate.php?success";
            header("Location: " . $url);
        }
    }
}
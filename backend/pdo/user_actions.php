<?php
include "fn.php";
include "../dao/UrlRepositoryImpl.php";
include "../dao/UserRepositoryImpl.php";

session_start();

if(isset($_GET["remove"]) && $_GET['code'] != null) {
    // Init param
    $urlImpl = new UrlRepositoryImpl();
    $userImpl = new UserRepositoryImpl();
    $code = $_GET['code'];
    $userArray = $urlImpl->getUrlObjectByCode($code);
    $returnUrl = "";

    // Check if the remover == user
    if($userArray != null) {
        if($_SESSION['method'] == "discord") {
            $codeRelatedUserId = $userArray['userid'];
            $userCurrentUserId = $userImpl->getUserProfileByDiscordID($_SESSION['userObject']->id)->getUid();
            if($codeRelatedUserId != "anonymous" && $codeRelatedUserId == $userCurrentUserId) {
                $urlImpl->removeUrlByCode($code);
                $returnUrl = "../../index.php";
            } else {
                $returnUrl = "../../403.php";
            }
        } else {
            $codeRelatedUserId = $userArray['userid'];
            $userCurrentUserId = unserialize($_SESSION['userObject'])->getUid();
            if($codeRelatedUserId != "anonymous" && $codeRelatedUserId == $userCurrentUserId) {
                $urlImpl->removeUrlByCode($code);
                $returnUrl = "../../index.php";
            } else {
                $returnUrl = "../../403.php";
            }
        }

        header("Location: " . $returnUrl);
    }
}

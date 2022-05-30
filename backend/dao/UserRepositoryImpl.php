<?php
require "../connection/mysql.php";
require "UserRepository.php";
require "../model/User.php";

class UserRepositoryImpl implements UserRepository
{
    public function createUser(User $user): string
    {
        // TODO: Implement createUser() method.
        // Discord Impl
        $id = $user->getUid();
        $username = $user->getUsername();
        $email = $user->getEmail();
        $timestamp = $user->getTimestamp();
        $status = $user->getStatus();

        if($user->getMemberID() == null) {
            $discordID = $user->getDiscordID();
            $statement = "INSERT INTO user_info VALUES ('$id', '$username', $discordID, 'N/A', '$email', $timestamp, '$status')";
        } else {
            $memberID = $user->getMemberID();
            $statement = "INSERT INTO user_info VALUES ('$id', '$username', 'N/A', '$memberID', '$email', $timestamp, '$status')";
        }

        $GLOBALS['udb']->query($statement);

        return $id;
    }

    public function updateUser($field, $value, $key)
    {
        // TODO: Implement updateUser() method.
    }

    public function removeUser($uid)
    {
        // TODO: Implement removeUser() method.
    }

    public function isUserExistByDiscordID($discordID) : bool
    {
        // TODO: Implement isUserExist() method.
        $statement = "SELECT uid FROM user_info WHERE discord_id = $discordID";

        if($GLOBALS['udb']->query($statement)->num_rows > 0) {
            return ture;
        } else {
            return false;
        }
    }


    public function getUserProfileByDiscordID($discordID)
    {
        // TODO: Implement getUserProfileByDiscordID() method.
        $statement = "SELECT * FROM user_info WHERE discord_id = $discordID";
        $q = $GLOBALS['udb']->query($statement);
        if($q->num_rows > 0) {
            $result = $q->fetch_assoc();

            $user = new User($result['username'], $result['email'], $result['timestamp'], $result['status'], true);
            $user->setDiscordID($result['discord_id']);
            $user->setUid($result['uid']);

            return $user;
        } else {
            return false;
        }
    }

    public function getUserProfileByMember($memberID)
    {
        // TODO: Implement getUserProfileByMember() method.
        $statement = "SELECT * FROM user_info WHERE member_id = '$memberID'";
        $q = $GLOBALS['udb']->query($statement);
        if($q->num_rows > 0) {
            $result = $q->fetch_assoc();

            $user = new User($result['username'], $result['email'], $result['timestamp'], $result['status'], true);
            $user->setMemberID($result['member_id']);
            $user->setUid($result['uid']);

            return $user;
        } else {
            return false;
        }

    }

    public function isUserExistByMemberID($memberID) : bool
    {
        // TODO: Implement isUserExistByMemberID() method.
        $statement = "SELECT * FROM user_info WHERE member_id = '$memberID'";

        if($GLOBALS['udb']->query($statement)->num_rows != 0) {
            return true;
        }
        return false;
    }
}
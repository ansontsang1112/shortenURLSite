<?php

interface UserRepository
{
    public function createUser(User $user);
    public function updateUser($field, $value, $key);
    public function removeUser($uid);

    public function isUserExistByDiscordID($discordID);
    public function isUserExistByMemberID($memberID);
    public function getUserProfileByDiscordID($discordID);
    public function getUserProfileByMember($memberID);

    public function isMemberLinked($uid);
}
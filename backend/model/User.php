<?php

class User {
    private $uid;
    private $username;
    private $discordID;
    private $memberID;
    private $email;
    private $timestamp;
    private $status;

    function __construct($username, $email, $timestamp, $status, $readOnly)
    {
        if(!$readOnly) $this->uid = uniqid();
        $this->username = $username;
        $this->email = $email;
        $this->timestamp = $timestamp;
        $this->status = $status;
    }

    /**
     * @return string
     */
    public function getUid()
    {
        return $this->uid;
    }

    /**
     * @param string $uid
     */
    public function setUid($uid)
    {
        $this->uid = $uid;
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param mixed $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @return mixed
     */
    public function getDiscordID()
    {
        return $this->discordID;
    }

    /**
     * @param mixed $discordID
     */
    public function setDiscordID($discordID)
    {
        $this->discordID = $discordID;
    }

    /**
     * @return mixed
     */
    public function getMemberID()
    {
        return $this->memberID;
    }

    /**
     * @param mixed $memberID
     */
    public function setMemberID($memberID)
    {
        $this->memberID = $memberID;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * @param mixed $timestamp
     */
    public function setTimestamp($timestamp)
    {
        $this->timestamp = $timestamp;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }


}
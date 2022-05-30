<?php

class Url {
    // Key
    private $key;

    // Body
    private $userCreated;
    private $createdTimestamp;
    private $clicked;
    private $urlRedirect;
    private $title;
    private $ip;
    private $status;

    /**
     * @param $userCreated
     * @param $createdTimestamp
     * @param $clicked
     * @param $urlRedirect
     * @param $title
     * @param $ip
     * @param $status
     * @param $readOnly
     */
    public function __construct($userCreated, $createdTimestamp, $clicked, $urlRedirect, $title, $ip, $status, $readOnly)
    {
        if(!$readOnly) $this->key = uniqid();
        $this->userCreated = $userCreated;
        $this->createdTimestamp = $createdTimestamp;
        $this->clicked = $clicked;
        $this->urlRedirect = $urlRedirect;
        $this->title = $title;
        $this->ip = $ip;
        $this->status = $status;
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


    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @param string $key
     */
    public function setKey($key)
    {
        $this->key = $key;
    }

    /**
     * @return mixed
     */
    public function getUserCreated()
    {
        return $this->userCreated;
    }

    /**
     * @param mixed $userCreated
     */
    public function setUserCreated($userCreated)
    {
        $this->userCreated = $userCreated;
    }

    /**
     * @return mixed
     */
    public function getCreatedTimestamp()
    {
        return $this->createdTimestamp;
    }

    /**
     * @param mixed $createdTimestamp
     */
    public function setCreatedTimestamp($createdTimestamp)
    {
        $this->createdTimestamp = $createdTimestamp;
    }

    /**
     * @return mixed
     */
    public function getClicked()
    {
        return $this->clicked;
    }

    /**
     * @param mixed $clicked
     */
    public function setClicked($clicked)
    {
        $this->clicked = $clicked;
    }

    /**
     * @return mixed
     */
    public function getUrlRedirect()
    {
        return $this->urlRedirect;
    }

    /**
     * @param mixed $urlRedirect
     */
    public function setUrlRedirect($urlRedirect)
    {
        $this->urlRedirect = $urlRedirect;
    }

    /**
     * @return mixed
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * @param mixed $ip
     */
    public function setIp($ip)
    {
        $this->ip = $ip;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }
}
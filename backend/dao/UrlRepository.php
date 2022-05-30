<?php

interface UrlRepository
{
    public function getUrlObjectByUser($userid);
    public function getUrlObjectByCode($code);
}
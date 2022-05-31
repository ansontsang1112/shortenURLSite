<?php

interface UrlRepository
{
    public function getUrlObjectByUser($userid);
    public function getUrlObjectByCode($code);

    public function replaceUrlOwner($oldOwner, $newOwner);

    public function removeUrlByCode($code);
}
<?php

class Link {
    private $linkID;
    private $postID;
    private $linkName;
    private $linkHref;

    public function __get($name) {
        return $this->$name;
    }
}

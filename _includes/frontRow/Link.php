<?php

class Link {
    private $linkID;
    private $postID;
    private $linkName;
    private $linkURL;
    
    public function __get($name) {
        return $this->$name;
    }
}
<?php

class Post {
    private $postID;
    private $title;
    private $content;
    private $commentsAllowed;
    private $dateTimePosted;
    
    public function __get($name) {
        return $this->$name;
    }
}
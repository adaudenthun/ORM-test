<?php
require_once "Post.php";

$post = New Post();

try{
    $post->load(5);
}catch (Exception $e){
    print_r($e->getMessage());
    die;
}

$posts = Post::find("content like '%bla%'");


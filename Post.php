<?php
require_once "Entity.php";


class Post extends Entity
{

    protected static $tableName = "posts";

    // int auto increment
    public $id;

    // varchar 255
    public $content;

    // varchar 255
    public $subcontent;

    /**
     * @return mixed
     */
    public function getSubcontent()
    {
        return $this->subcontent;
    }

    /**
     * @param mixed $subcontent
     */
    public function setSubcontent($subcontent)
    {
        $this->subcontent = $subcontent;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param mixed $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }
}
<?php 
    error_reporting(E_ALL ^ E_WARNING);     
    spl_autoload_register(function ($class_name) {
        include  $_SERVER['DOCUMENT_ROOT'].'/modules/Post/'.$class_name . '.php';
        include  $_SERVER['DOCUMENT_ROOT'].'/modules/Category/'.$class_name . '.php';
        include  $_SERVER['DOCUMENT_ROOT'].'/modules/User/'.$class_name . '.php';
        include  $_SERVER['DOCUMENT_ROOT'].'/includes/classes/'.$class_name . '.php';
    });
    
    $database = new Database();
 class Post{
     public static $table = "post";

     public static function Create(){
         $title = $_POST['title'];
         $content = $_POST['content'];
         Database::Connect();
         Database::Query("INSERT INTO post (title, categoryId, content) VALUES ('$title', 1 ,'$content' ) ");
         echo "Successfully added post!";
     }

     public static function SelectAll(){
        Database::Connect();
        $result = Database::Query("SELECT * FROM post");
        return $result;
    }
 }
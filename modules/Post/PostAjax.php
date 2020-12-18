<?php 
    error_reporting(E_ALL ^ E_WARNING);     
    spl_autoload_register(function ($class_name) {
        include  $_SERVER['DOCUMENT_ROOT'].'/modules/Post/'.$class_name . '.php';
        include  $_SERVER['DOCUMENT_ROOT'].'/modules/Category/'.$class_name . '.php';
        include  $_SERVER['DOCUMENT_ROOT'].'/modules/User/'.$class_name . '.php';
        include  $_SERVER['DOCUMENT_ROOT'].'/includes/classes/'.$class_name . '.php';
    });

    $post = new Post();

    $function_to_call =  $_POST['function'];
    call_user_func($function_to_call);

    function AddPost(){
        Post::Create($_POST['title'], $_POST['content']);
    }

    ?>


   

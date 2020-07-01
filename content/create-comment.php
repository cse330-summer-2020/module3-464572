<?php
    session_start();
    require("shared.php");
    token_check();

    $author = $_SESSION['username'];
    $body = $_POST['body'];
    $story_pk = (int)$_POST['story_pk'];

    require("../database.php");

    $query_string = "INSERT into comments (username, story_pk, body) values(?, ?, ?)";
    $bind_string = 'sis';
    $data = array($author, $story_pk, $body);
    $msg = 'Comment';

    if (insert_data($query_string, $bind_string, $data, $msg)){
        header("Location: view-comments.php?story_pk=$story_pk");
    }
    
    
    
?>
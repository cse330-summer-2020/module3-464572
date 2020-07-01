<?php
    session_start();
    require("shared.php");
    token_check();

    $table = "comments";
    $comments_pk = (int)$_POST['comments_pk'];
    $story_pk = (int)$_POST['story_pk'];


    // update database. Needs to first delete all likes associated with that comment.
    require ("../database.php");

    $query_string = "DELETE from likes_comments WHERE (comments_pk=?)";
    $mysqli = connect();
    $stmt = $mysqli->prepare($query_string);
    if(!$stmt){
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }

    $stmt->bind_param('i', $comments_pk);

    if ($stmt->execute()){
        printf("Successful deletion");
        $stmt->close();
    }else{
        printf("Failed deletion");
        exit;
    }



    $query_string="DELETE from $table WHERE (comments_pk=?)";
    $mysqli = connect();
    $stmt = $mysqli->prepare($query_string);
    if(!$stmt){
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }

    $stmt->bind_param('i', $comments_pk);

    if ($stmt->execute()){
        printf("Successful deletion");
        header("Location: view-comments.php?story_pk=$story_pk");
        $stmt->close();
    }else{
        printf("Failed deletion");
    }

    
    
?>
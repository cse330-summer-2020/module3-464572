<?php
    session_start();
    require("shared.php");
    token_check();

    if ($_SESSION['username'] !== $_POST['author']){
        die("Session username does not match the author. Exiting.");
    }

    require("../database.php");
    $body = $_POST['new-body'];
    $comments_pk = $_POST['comments_pk'];
    $story_pk = $_POST['story_pk'];

    

    $stmt = $mysqli->prepare("UPDATE comments SET username=?, story_pk=?, body=? WHERE comments_pk=?");
    if(!$stmt){
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }

    $stmt->bind_param('sisi', $_SESSION['username'], $story_pk, $body, $comments_pk);

    if ($stmt->execute()){
        printf("Edit uploaded successfully.");
    }else{
        printf("Edit failed.");
    }

    $stmt->close();
    
?>
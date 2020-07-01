<?php
    session_start();
    require("shared.php");
    token_check();

    $author = $_SESSION['username'];
    $body = $_POST['body'];
    $story_pk = (int)$_POST['story_pk'];

    require("../database.php");
    $tablename = "comments";
    $query_string = "INSERT into $tablename (username, story_pk, body) values(?, ?, ?)";
    $stmt = $mysqli -> prepare($query_string);
    if(!$stmt){
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }

    $stmt->bind_param('sis', $author, $story_pk, $body);

    $execute_success = $stmt->execute();
    if ($execute_success){
        printf("Successful Comment insertion.");
        $stmt->close();
        header('Location: main.php');
        exit;
    }else{
        printf("Failed Comment insertion");
        $stmt->close();
        exit;
    }

    
?>
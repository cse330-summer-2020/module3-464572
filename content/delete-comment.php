<?php
    session_start();
    require("shared.php");
    token_check();

    $table = "comments";
    $comments_pk = (int)$_POST['comments_pk'];
    $query_string="DELETE from $table WHERE (comments_pk=?)";


    // update database
    require ("../database.php");
    $mysqli = connect();
    $stmt = $mysqli->prepare($query_string);
    if(!$stmt){
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }

    $stmt->bind_param('i', $comments_pk);

    if ($stmt->execute()){
        printf("Successful deletion");
    }else{
        printf("Failed deletion");
    }

    $stmt->close();
?>
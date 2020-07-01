<?php
    session_start();

    require("shared.php");
    token_check(); // CSRF Token Check

    // Filter Inputs, Variable Assignment
    $user = (string)$_SESSION["username"];
    $title = (string)$_POST["title"];
    $link = filter_var((string)$_POST["link"], FILTER_SANITIZE_URL);
    


    // Upload to server
    require("../database.php");

    // Table Insertion
    $table_name = "stories";
    $stmt = $mysqli->prepare("INSERT into " . $table_name . "(username, title, link) values (?, ?, ?)");
    if(!$stmt){
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }

    $stmt->bind_param('sss', $user, $title, $link);

    $execute_success = $stmt->execute();
    if ($execute_success){
        printf("Successful Story insertion.");
        $stmt->close();
        header('Location: main.php');
        exit;
    }else{
        printf("Failed Story insertion");
        $stmt->close();
        exit;
    }    
    
?>
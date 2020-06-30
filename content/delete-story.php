<!-- 
    Todo:
     - CSRF Token Check
     - Session Username check
 -->
<?php
    session_start();
    require("shared.php");
    token_check();

    if ($_SESSION['username'] !== $_POST['author']){
        die("Session username does not match author name, exiting.");
    }

    $table = "stories";
    $story_pk = (int)$_POST['story_pk'];
    $query_string="DELETE from $table WHERE (story_pk=?)";


    // update database
    require ("../database.php");
    $mysqli = connect();
    $stmt = $mysqli->prepare($query_string);
    if(!$stmt){
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }
    
    $stmt->bind_param('i', $story_pk);
    
    if ($stmt->execute()){
        printf("Successful deletion");
    }else{
        printf("Failed deletion");
    }
    
    $stmt->close();
?>
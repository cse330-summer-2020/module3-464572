<!-- 
    Todo:
     - CSRF Token Check
     - Session Username check
     - Deleting a story should also delete all comments and likes associated with it
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

    


    // update database
    require ("../database.php");


    // Delete associated story likes, comment likes, and comments linked to that story

    // Deleting all story likes
    $mysqli = connect();
    $query_string = "DELETE from likes_stories WHERE (story_pk=?)";
    $stmt = $mysqli->prepare($query_string);
    if(!$stmt){
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }
    
    $stmt->bind_param('i', $story_pk);
    
    if ($stmt->execute()){
        printf("Successful story like deletion");
    }else{
        printf("Failed story like deletion");
        
    }
    $stmt->close();


    // Finding all comment likes by finding all the comments_pk of all comments with story_pk 
    $query_string = "SELECT comments_pk FROM comments WHERE story_pk=$story_pk";
    $target_comments = get_data($query_string);
    foreach ($target_comments as $comment){
        $comments_pk_tmp = $comment['comments_pk'];
        $mysqli = connect();
        $query_string = "DELETE from likes_comments WHERE (comments_pk  =?)";
        $stmt = $mysqli->prepare($query_string);
        if(!$stmt){
            printf("Query Prep Failed: %s\n", $mysqli->error);
            exit;
        }
        
        $stmt->bind_param('i', $comments_pk_tmp);
        
        if ($stmt->execute()){
            printf("Successful comment like deletion");
        }else{
            printf("Failed comment like deletion");
            
        }
        $stmt->close();
    }

    $mysqli = connect();
    $query_string = "DELETE from comments WHERE (story_pk=?)";
    $stmt = $mysqli->prepare($query_string);
    if(!$stmt){
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }
    
    $stmt->bind_param('i', $story_pk);
    
    if ($stmt->execute()){
        printf("Successful story like deletion");
    }else{
        printf("Failed story like deletion");
        
    }
    $stmt->close();




    $query_string="DELETE from $table WHERE (story_pk=?)";
    $mysqli = connect();
    $stmt = $mysqli->prepare($query_string);
    if(!$stmt){
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }
    
    $stmt->bind_param('i', $story_pk);
    
    if ($stmt->execute()){
        printf("Successful deletion");
        header('Location: main.php');
    }else{
        printf("Failed deletion");
        
    }
    $stmt->close();
    
?>
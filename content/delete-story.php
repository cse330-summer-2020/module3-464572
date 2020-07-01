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
    $query_string = "DELETE from likes_stories WHERE (story_pk=?)";
    $bind_string = 'i';
    $data = array($story_pk);
    $msg = 'Story Likes';
    delete_data($query_string, $bind_string, $data, $msg);


    // Retrieve all comments tied to a specific story 
    $query_string = "SELECT comments_pk FROM comments WHERE story_pk=$story_pk";
    $target_comments = get_data($query_string);
    
    // For each comment, delete all associated likes
    foreach ($target_comments as $comment){
        $comments_pk_tmp = $comment['comments_pk'];

        $query_string = "DELETE from likes_comments WHERE (comments_pk =?)";
        $bind_string = 'i';
        $data = array($comments_pk_tmp);
        $msg = 'Comment Likes';
        delete_data($query_string, $bind_string, $data, $msg);
    }

    // Delete all comments associated with the story
    $query_string = "DELETE from comments WHERE (story_pk=?)";
    $bind_string = 'i';
    $data = array($story_pk);
    $msg = 'Comments';
    delete_data($query_string, $bind_string, $data, $msg);

    
    // Delete the story
    $query_string="DELETE from stories WHERE (story_pk=?)";
    $bind_string = 'i';
    $data = array($story_pk);
    $msg = 'Stories';
    if (delete_data($query_string, $bind_string, $data, $msg)){
        header('Location: main.php');
    }
?>
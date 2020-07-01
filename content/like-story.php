<!-- 
    Liking a story should 
     - insert a new entry into likes_stories
     - update the corresponding entry in stories
 -->
<?php
    session_start();
    require("shared.php");
    token_check();

    require('../database.php');

    $story_pk = $_POST['story_pk'];
    $username = $_SESSION['username'];

    // Table Insertion
    $table_name = "likes_stories";
    $stmt = $mysqli->prepare("INSERT into $table_name (username, story_pk) values (?, ?)");
    if(!$stmt){
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }

    $stmt->bind_param('si', $username, $story_pk);

    $execute_success = $stmt->execute();
    if ($execute_success){
        printf("Successful Story Like.");
        // Update the stories Table: first retrieve how many likes it currently has and then add 1
        $query_string = "SELECT likes FROM stories WHERE (story_pk=$story_pk)";
        $new_likes = get_data($query_string)[0]['likes'] + 1;
        $stmt = $mysqli->prepare("UPDATE stories SET likes=? WHERE story_pk=?");
        print_r($new_likes);
        if(!$stmt){
            printf("Query Prep Failed: %s\n", $mysqli->error);
            exit;
        }

        $stmt->bind_param('ii', $new_likes, $story_pk);

        if ($stmt->execute()){
            printf("Total likes uploaded successfully.");
        }else{
            printf("Total likes failed.");
        }

        $stmt->close();
        header("Location: view-comments.php?story_pk=$story_pk");
        exit;
    }else{
        printf("Failed Story Like");
        $stmt->close();
        exit;
    }

    
    

?>
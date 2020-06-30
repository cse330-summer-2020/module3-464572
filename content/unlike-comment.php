<!-- 
    Unliking a comment should 
     - insert a new entry into likes_comments
     - update the corresponding entry in comments
 -->
 <?php
    session_start();
    require("shared.php");
    token_check();

    require('../database.php');

    $comments_pk = $_POST['comments_pk'];
    $username = $_SESSION['username'];

    // Table Deletion
    $table_name = "likes_comments";
    $stmt = $mysqli->prepare("DELETE from $table_name WHERE (username=? AND comments_pk=?)");
    if(!$stmt){
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }

    $stmt->bind_param('si', $username, $comments_pk);

    $execute_success = $stmt->execute();
    if ($execute_success){
        printf("Successful comment UnLike.");
        // Update the comments Table: first retrieve how many likes it currently has and then add 1
        $query_string = "SELECT likes FROM comments WHERE (comments_pk=$comments_pk)";
        $new_likes = get_data($query_string)[0]['likes'] - 1;
        $stmt = $mysqli->prepare("UPDATE comments SET likes=? WHERE comments_pk=?");
        print_r($new_likes);
        if(!$stmt){
            printf("Query Prep Failed: %s\n", $mysqli->error);
            exit;
        }

        $stmt->bind_param('ii', $new_likes, $comments_pk);

        if ($stmt->execute()){
            printf("Total likes decremented successfully.");
        }else{
            printf("Total likes failed to decrement.");
        }

        $stmt->close(); 
        exit;
    }else{
        printf("Failed comments unlike");
        exit;
    }

    $stmt->close();
    
?>
<!-- 
    Liking a Comment should 
     - insert a new entry into likes_comments
     - update the corresponding entry in comments
 -->
 <?php
    session_start();
    require("shared.php");
    token_check();

    require('../database.php');

    $comment_pk = $_POST['comments_pk'];
    $username = $_SESSION['username'];

    // Table Insertion
    $table_name = "likes_comments";
    $stmt = $mysqli->prepare("INSERT into $table_name (username, comments_pk) values (?, ?)");
    if(!$stmt){
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }

    $stmt->bind_param('si', $username, $comment_pk);

    $execute_success = $stmt->execute();
    if ($execute_success){
        printf("Successful Comment Like.");
        // Update the stories Table: first retrieve how many likes it currently has and then add 1
        $query_string = "SELECT likes FROM comments WHERE (comments_pk=$comment_pk)";
        $new_likes = get_data($query_string)[0]['likes'] + 1;
        $stmt = $mysqli->prepare("UPDATE comments SET likes=? WHERE comments_pk=?");
        print_r($new_likes);
        if(!$stmt){
            printf("Query Prep Failed: %s\n", $mysqli->error);
            exit;
        }

        $stmt->bind_param('ii', $new_likes, $comment_pk);

        if ($stmt->execute()){
            printf("Total likes uploaded successfully.");
        }else{
            printf("Total likes failed.");
        }

        $stmt->close();
        header('Location: main.php');
        exit;
    }else{
        printf("Failed Comment Like");
        $stmt->close();
        exit;
    }

    
    

?>
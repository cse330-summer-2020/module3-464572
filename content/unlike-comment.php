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
    $story_pk = $_POST['story_pk'];

    // Table Deletion -> Data Retrieval -> Edit Likes
    $query_string = "DELETE from likes_comments WHERE (username=? AND comments_pk=?)";
    $bind_string = 'si';
    $data = array($username, $comments_pk);
    $msg = 'Likes on comments';

    if (delete_data($query_string, $bind_string, $data, $msg)){
        // First ask server how many total likes to calculate new likes
        $query_string = "SELECT likes FROM comments WHERE (comments_pk=$comments_pk)";
        $new_likes = get_data($query_string)[0]['likes'] - 1;

        $query_string = "UPDATE comments SET likes=? WHERE comments_pk=?";
        $bind_string = 'ii';
        $data = array($new_likes, $comments_pk);
        $msg = 'Likes on comments';
        if (edit_data($query_string, $bind_string, $data)){
            header("Location: view-comments.php?story_pk=$story_pk");
        }
    }    
    
?>
<?php
    session_start();
    require('shared.php');
    token_check();

    if ($_SESSION['username'] !== $_POST['author']){
        die("Session username does not match this comment's author. Exiting.");
    }


    // Retrieve old data
    $comments_pk = $_POST['comments_pk'];
    $query_string = "SELECT body, story_pk from comments WHERE (comments_pk = $comments_pk)";
    $comment_data = get_data($query_string)[0];
    $body = $comment_data['body'];
    $story_pk = $comment_data['story_pk'];
    $token = $_SESSION['token'];
    printf(
        "<form action=\"upload-comment-edit.php\" method=\"POST\">
            <label for=\"new-comment-input\">Body:</label>\n
            <textarea id=\"new-comment-input\" name=\"new-body\" maxlength=\"300\" rows=\"4\" cols=\"50\">$body</textarea><br>\n 

            <input type=\"hidden\" value=\"$story_pk\" name=\"story_pk\">\n

            <input type=\"hidden\" name=\"comments_pk\" value=\"$comments_pk\" />
            <input type=\"hidden\" name=\"token\" value=\"$token\" />
            <input type=\"submit\" value=\"Submit Changes\">\n
        </form>
    ");
?>
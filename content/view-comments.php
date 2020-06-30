<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comments</title>
</head>
<body>
    <!-- Display the Stories Title -->
    <!-- 
        Todo:
        Comment Form
        Get data associated with story_pk
        Get all the data about comments whose story_pk matches the $_GET['story_pk']
        Display Both
     -->
    <header>
        <div>E News</div>
        <form action="../logout.php">
            <input type="submit" value="Logout">
        </form>
    </header>
    
    <form action="create-comment.php" method="POST">
        <label for="comment_input">Create a comment:</label>
        <textarea id="comment_input" name="body"></textarea>
        <input type="submit" value="Comment">
        <input type="hidden" name="token" value=<?php session_start(); echo $_SESSION['token']?>>
        <input type="hidden" name="story_pk" value=<?php echo $_GET['story_pk']?>>
    </form>
    
    <?php
        require("shared.php");
        $story_pk = $_GET['story_pk'];
        $token = $_SESSION['token'];

        /* Retrieving Story and Comment Data */
        // Table:
        // story_pk, username, title, link
        $query_string = "SELECT username, title, link, likes FROM stories WHERE (story_pk=$story_pk)";
        $story_data = get_data($query_string)[0]; // story_data is an associative array

        //print_r($story_data);
        $story_username = $story_data['username'];
        $story_title = $story_data['title'];
        $story_link = $story_data['link'];
        $story_likes = $story_data['likes'];
        
        // Retrieve Comments
        $query_string = "SELECT comments_pk, username, body, likes FROM comments WHERE (story_pk=$story_pk)";
        $comments_data = get_data($query_string);

        // Iterate through each entry in $comments_data
        $comments_username  = '';
        $comments_body = '';

        // Check if the current user has liked the current story
        $current_user = $_SESSION['username'];
        $query_string = "SELECT story_pk from likes_stories WHERE (username='$current_user' AND story_pk=$story_pk)";
        $likes_comments_data = get_data($query_string);
        print_r($comments_data);

        $story_already_liked = false;
        if (count($likes_comments_data) === 1){
            $story_already_liked = true;
        }

        /* Displaying Data */
        // Story Display

        if ($story_already_liked){
            printf("
            <div class='story-wrapper'>\n
                <h3><a href=\"%s\">%s</a></h3>\n
                <div>%s</div>\n
                <div>Likes: %u</div>\n
                <form action='unlike-story.php' method='POST'>
                    <input type='submit' value='Unlike'>
                    <input type='hidden' value='$story_pk' name='story_pk'>
                    <input type=\"hidden\" name=\"token\" value=\"$token\" />\n
                </form>\n
            </div>\n
        ", $story_link, $story_title, $story_username, $story_likes);
        }else{
            printf("
            <div class='story-wrapper'>\n
                <h3><a href=\"%s\">%s</a></h3>\n
                <div>%s</div>\n
                <div>Likes: %u</div>\n
                <form action='like-story.php' method='POST'>
                    <input type='submit' value='Like'>
                    <input type='hidden' value='$story_pk' name='story_pk'>
                    <input type=\"hidden\" name=\"token\" value=\"$token\" />\n
                </form>\n
            </div>\n
        ", $story_link, $story_title, $story_username, $story_likes);
        }
        


        // Add edit and delete buttons if the author matches the session user
        
        //  print_r($comments_data);
        printf("<ul>\n");
        foreach ($comments_data as $entry){
            $update_buttons="";
            if ($entry['username'] === (string)$_SESSION['username']){
                $username_tmp = $entry['username'];
                $body_tmp = $entry['body'];
                $comment_pk_tmp = $entry['comments_pk'];
                $update_buttons = 
                "<form action=\"edit-comment.php\" method=\"POST\">\n
                    <input type=\"hidden\" value=\"$username_tmp\" name=\"author\">\n
                    <input type=\"hidden\" value=\"$comment_pk_tmp\" name=\"comments_pk\">\n
                    <input type=\"hidden\" name=\"token\" value=\"$token\" />\n
                    <input type=\"submit\" value=\"Edit\">\n
                </form>\n
                <form action=\"delete-comment.php\" method=\"POST\">\n
                    <input type=\"hidden\" value=\"$username_tmp\" name=\"author\">\n
                    <input type=\"hidden\" value=\"$comment_pk_tmp\" name=\"comments_pk\">\n
                    <input type=\"hidden\" name=\"token\" value=\"$token\" />\n
                    <input type=\"submit\" value=\"Delete\">\n
                </form>\n";
            }
            printf(
            "<li>\n
                <div>%s: %s</div>\n
                <div>Likes: %u</div>\n
                <div class=\"comment-buttons-wrapper\">%s</div>", $entry['username'], $entry['body'], $entry['likes'], $update_buttons);
            
            printf("</li>\n");
        }
        printf("</ul>\n");
    ?>
</body>
</html>
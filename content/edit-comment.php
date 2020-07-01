<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Comment</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/edit-story.css">
</head>
<body>
    <header class='container-fluid'>
        <h2><a href='main.php' class='home-link'>E News</a></h2>
        <form action="../logout.php" class='logout-block'>
            <p>
                <a href="your-likes.php?username=<?php session_start(); echo $_SESSION['username']; ?>">
                    <?php echo $_SESSION['username'];?>
                </a>
            </p>
            <input type="submit" value="Logout">
        </form>
    </header>
    <main>
    <?php
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
    </main>
    
</body>
</html>
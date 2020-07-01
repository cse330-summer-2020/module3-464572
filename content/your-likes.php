<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Likes</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/your-likes.css">
</head>
<body>
    <!-- 
        Get the likes associated with a specific username, for both likes_stories and likes_comments
     -->
    <?php
        session_start();
        if ($_SESSION['username'] != $_GET['username']){
            die('Session username does not match the user you are trying to view. Exiting');
        }

        require("shared.php");
        $current_user = $_SESSION['username'];
        $query_string=
        "SELECT stories.story_pk, stories.title, stories.link, stories.username, stories.likes FROM 
        stories join likes_stories on (stories.story_pk=likes_stories.story_pk)
        WHERE (likes_stories.username = '$current_user')";

        $liked_stories = get_data($query_string);

        $query_string=
        "SELECT comments.comments_pk, comments.body, comments.username, comments.likes FROM 
        comments join likes_comments on (comments.comments_pk=likes_comments.comments_pk)
        WHERE (likes_comments.username = '$current_user')";

        $liked_comments = get_data($query_string);
    ?>

    <header class="container-fluid">
        <h2><a href='main.php' class='home-link'>E News</a></h2>
        <form action="../logout.php" class="logout-block">
            <p>
                <a href="your-likes.php?username=<?php echo $_SESSION['username']; ?>">
                    <?php echo $_SESSION['username'];?>
                </a>
            </p>
            <input type="submit" value="Logout">
        </form>
    </header>

    <!-- Data Display -->
    <main>
        <h3>Liked Stories</h3>
        <ul>
            <?php
                foreach($liked_stories as $story){
                    $story_pk_tmp = $story['story_pk'];
                    
                    $story_link_tmp = $story['link'];
                    $story_title_tmp = $story['title'];
                    $story_author = $story['username'];
                    $story_total_likes = $story['likes'];

                    printf("
                    <li>
                        <div>
                            <a href='%s'>%s</a>
                        </div>
                        <div class='sub-info'>
                            <div>Author: %s</div>
                            <div>Likes: %u</div>
                        </div>
                        <hr>
                    </li>
                    ", $story_link_tmp, $story_title_tmp, $story_author, $story_total_likes);
                }
            ?>
        </ul>

        <h3>Liked Comments</h3>
        <ul>
            <?php
                foreach($liked_comments as $comments){
                    $comments_pk_tmp = $comments['comments_pk'];
                    
                    $comments_body_tmp = $comments['body'];
                    $comments_author = $comments['username'];
                    $comments_total_likes = $comments['likes'];

                    printf("
                    <li>
                        <p>%s</p>
                        <div class='sub-info'>
                            <div>Author: %s</div>
                            <div>Likes: %u</div>
                        </div>
                        <hr>
                    </li>
                    
                    ", $comments_body_tmp, $comments_author, $comments_total_likes);
                }
            ?>
        </ul>
    </main>
    
</body>
</html>
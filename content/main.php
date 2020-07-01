<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E News</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/main.css">
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
        <!-- Create Story Form-->
        <form action="create-story.php" method="POST">
            <h3>Post a Story!</h3>
            <label for="story_title">Title:</label>
            <input type="text" name="title" id="story_title" maxlength="300" required><br>

            <label for="story_link">Link to Article:</label>
            <input type="text" name="link" id="story_link" maxlength="1000" required><br>

            <input type="hidden" name="token" value="<?php echo $_SESSION['token'];?>">

            <input type="submit" value="Post Story">
        </form>
        <hr>

        <!-- PHP to retrieve ALL stories and list them. -->
        <?php
            $user = (string)$_SESSION["username"];

            require("../database.php");
            $stmt = $mysqli->prepare("SELECT story_pk, username, title, link from stories");
            if(!$stmt){
                printf("Query Prep Failed: %s\n", $mysqli->error);
                exit;
            }

            $stmt->execute();

            $stmt->bind_result($story_pk_tmp, $username_tmp, $title_tmp, $link_tmp);

            $token = $_SESSION['token'];

            echo "<ul class=\"story-list\">\n";
            while($stmt->fetch()){
                
                $story_html_output = 
                "\t<li>\n
                    <div class=\"story-title\">%s</div>\n
                    <div class=\"user\">%s</div>\n
                    <a href=\"view-comments.php?story_pk=$story_pk_tmp\" class=\"comment-link\">Comments</a>\n
                </li>\n";

                // If the current user matches $username_tmp, also print edit and delete buttons
                if ($user === $username_tmp){
                    $story_html_output = 
                    "\t<li>\n
                        <div class=\"story-title\">%s</div>\n
                        <div class=\"user\">%s</div>\n
                        <a href=\"view-comments.php?story_pk=$story_pk_tmp\" class=\"comment-link\">Comments</a>\n
                        <div class=\"story-button-wrapper\">\n
                            <form action=\"edit-story.php\" method=\"POST\">\n
                                <input type=\"hidden\" value=\"$username_tmp\" name=\"author\">\n
                                <input type=\"hidden\" value=\"$story_pk_tmp\" name=\"story_pk\">\n
                                <input type=\"submit\" value=\"Edit\">\n
                            </form>\n
                            <form action=\"delete-story.php\" method=\"POST\">\n
                                <input type=\"hidden\" value=\"$username_tmp\" name=\"author\">\n
                                <input type=\"hidden\" value=\"$story_pk_tmp\" name=\"story_pk\">\n
                                <input type=\"hidden\" name=\"token\" value=\"$token\" />\n
                                <input type=\"submit\" value=\"Delete\">\n
                            </form>\n
                        </div>\n
                        <hr>\n
                    </li>\n";
                }

                printf(
                    $story_html_output,
                    "<a href=\"" . htmlspecialchars($link_tmp) ."\">" . htmlspecialchars($title_tmp) . "</a>",
                    htmlspecialchars($username_tmp)
                );
                
            }
            echo "</ul>\n";
            
            $stmt->close();
        ?>
    </main>
    
</body>
</html>
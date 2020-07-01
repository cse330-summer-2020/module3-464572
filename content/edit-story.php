<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E News: Edit Story</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/edit-story.css">
</head>
<body>
    <!-- 
    To Do:
     - Username Check by retrieving the user associated with a story
     - Display the old post
     - CSRF Token Check
     - POST form
    -->
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
    <h3>Current Version</h3>
    <?php
        require("../database.php");
        $story_pk = (int)$_POST['story_pk'];
        
        

        // Retrieve Old Text and Author
        $stmt = $mysqli->prepare("SELECT username, title, link FROM stories WHERE (story_pk=$story_pk)");
        if(!$stmt){
            printf("Query Prep Failed: %s\n", $mysqli->error);
            exit;
        }

        $stmt->execute();

        $stmt->bind_result($author, $title, $link);

        echo "<ul>\n";
        $counter = 0;
        while($stmt->fetch()){
            $counter++;
        }
        if ($counter != 1){
            die("Unexpected Case: This story does not have 1 author, dying.");
        }
        echo "</ul>\n";
        $stmt->close();


        // Check that the current user is in fact the author
        if ($_SESSION['username'] !== $author){
            die("Session name does not match the author of this post. Exiting.");
        }

        //Filter, unsure if needed for a default form value
        $title = htmlentities($title);
        $link = filter_var($link, FILTER_SANITIZE_URL);

        $token = $_SESSION['token'];

        // Display Old Values
        printf(
            "<form action=\"upload-story-edit.php\" method=\"POST\">\n
                <label for=\"new-title-input\">Title:</label>\n
                <textarea id=\"new-title-input\" name=\"new-title\" maxlength=\"300\" rows=\"4\" cols=\"50\">$title</textarea><br>\n 
                
                <label for=\"new-link-input\">Link:</label>\n
                <input id=\"new-link-input\" type=\"text\" name=\"new-link\" maxlength=\"1000\" value=\"$link\">\n

                <input type=\"hidden\" name=\"story_pk\" value=\"$story_pk\" />
                <input type=\"hidden\" name=\"token\" value=\"$token\" />
                <input type=\"submit\" value=\"Submit Changes\">\n
            </form>\n"
        )
        
    ?>
    </main>
    
    
</body>
</html>

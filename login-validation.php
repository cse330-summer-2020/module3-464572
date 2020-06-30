<?php

    $raw_username = (string)$_POST['raw_username'];
    $raw_password = (string)$_POST['raw_password'];
    // Sanity Check Regex Filter the username and password
    if (!preg_match("/[A-Za-z0-9]+/", $raw_username) || !preg_match('/[A-Za-z0-9]+/', $raw_password)){
        printf("The inputted username and inputted password must only be alphanumeric. Stopping.");
        exit;
    }


    /*----------------------------------- Database Validation */
    require("database.php");
    // Use a prepared statement
    $stmt = $mysqli->prepare("SELECT COUNT(*), password_hash FROM users WHERE username=?");

    // Bind the parameter
    $stmt->bind_param('s', $user);
    $user = $_POST['raw_username'];
    $stmt->execute();

    // Bind the results
    $stmt->bind_result($cnt, $pwd_hash);
    $stmt->fetch();

    $pwd_guess = $_POST['raw_password'];
    // Compare the submitted password to the actual password hash

    if($cnt == 1 && password_verify($pwd_guess, $pwd_hash)){
        // Login succeeded!
        printf("Login success");
        session_start();
        $_SESSION['username'] = (string)$_POST['raw_username'];
        $_SESSION['token'] = bin2hex(random_bytes(32)); // CSRF Token

        header("Location: content/main.php");
    } else{
        // Login failed; redirect back to the login screen
        printf("Login failed.");
    }

?>
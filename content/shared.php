<!-- 
    This file is meant to be the central source of functions used in multiple files.
 -->
<?php

    // Equivalent to importing database.php
    function connect(){
        $mysqli = new mysqli('localhost', 'module3-user', 'module3-password', 'module3');

        if($mysqli->connect_errno) {
            printf("Connection Failed: %s\n", $mysqli->connect_error);
            exit;
        }
        return $mysqli;
    }

    // A bit tricky to implement as I need to explicitly type in bind_param(), gonna not implement
    /* function update_data($query_string, $inputs){
        $mysqli = connect();
        $stmt = $mysqli->prepare($query_string);
        if(!$stmt){
            printf("Query Prep Failed: %s\n", $mysqli->error);
            exit;
        }
        
        $stmt->bind_param('sss', $first, $last, $dept);
        
        if ($stmt->execute()){
            printf("Successful update");
        }else{
            printf("Failed update");
        }
    
        
        $stmt->close();
    } */

    // Does a function call get access to $_SESSION and $_POST? Yes, it seems so.
    // Similarly, session_start() does not need to be recalled inside a function.
    function token_check(){
        $token = $_POST["token"];
        // printf("Session Token: " . $_SESSION['token'] . "\n");
        // printf("Post Token: " . $_POST['token'] . "\n");
        if(!hash_equals($_SESSION['token'], $_POST['token'])){
            printf("Session Token: " . $_SESSION['token'] . "\nPost Token" . $_POST['token']);
            die("Mismatched tokens. Request forgery detected");
        }
    }

    // Input a query string and returns an array of associative arrays. Each item in $all_data is a single entry.
    function get_data($query_string){
        $mysqli = connect();
        $stmt = $mysqli->prepare($query_string);
        if(!$stmt){
            printf("Query Prep Failed: %s\n", $mysqli->error);
            exit;
        }

        if (!$stmt->execute()){
            die("While getting data, query failed to execute");
        }

        $result = $stmt->get_result();

        $all_data = array();
        while($row = $result->fetch_assoc()){
            array_push($all_data, $row);
        }
        $stmt->close();

        return $all_data;
    }
    

?>
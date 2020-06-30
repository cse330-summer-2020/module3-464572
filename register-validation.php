<!-- 
    Steps:
    1. Get Database access
    2. Filter input
    3. Prepare the password by hashing + salting
    4. Store the Username + Hash in MySql
 -->
 <!-- 
    Progress:
    I can successfully upload to MySQL, now need to hash password and upload that instead of the pw.
  -->
<?php
    // Get database access;

    // Table Description:
    // users - VARCHAR(25)
    // password_hash - VARCHAR(255)
    require('database.php');
    $table_name = 'users';



    $username = (string)$_POST['raw_username'];
    $password = (string)$_POST['raw_password'];

    // Hash + Salt prep
    $password_hash = password_hash($password, PASSWORD_DEFAULT);


    // Table Insertion
    $stmt = $mysqli->prepare("INSERT into " . $table_name . "(username, password_hash) values (?, ?)");
    if(!$stmt){
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }

    $stmt->bind_param('ss', $username, $password_hash);

    $execute_success = $stmt->execute();
    if ($execute_success){
        printf("Successful user insertion.");
        exit;
    }else{
        printf("Failed");
        exit;
    }

    $stmt->close();
?>
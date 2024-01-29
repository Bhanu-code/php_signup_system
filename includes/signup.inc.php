<?php

if($_SERVER["REQUEST_METHOD"] === "POST"){
    $username = $_POST["username"];
    $pwd = $_POST["pwd"];
    $email = $_POST["email"];

    try{
        require_once 'dbh.inc.php';
        require_once 'signup_model.inc.php';
        require_once 'signup_controller.inc.php';
        // require_once 'signup_view.inc.php';

        //ERROR HANDLERS
        $errors = [];

        if(is_input_empty($username, $pwd, $email)){
            $errors["empty_input"] = "fill in all fields";
        }
        if(!is_email_valid($email)){
            $errors["invalid_email"] = "invalid email!";
        }
        if(is_username_taken($pdo, $username)){
            $errors["username_taen"] = "username taken";
        }
        if(is_email_registered($pdo, $email)){
            $errors["email_used"] = "email used!";
        }

        require_once'config_session.inc.php';

        if($errors){
            $_SESSION["error_signup"] = $errors;
            header("Location: ../index.php");
            die();
        }

        create_user($pdo, $username, $pwd, $email);

        header("Location: ../index.php?signup=success");

        $pdo = null;
        $stmt = null;
        
        die();


    } catch (PDOException $e){
        die("Query failed: ". $e->getMessage());
    }
} else {
    header("Location: ../index.php");
    die();
}
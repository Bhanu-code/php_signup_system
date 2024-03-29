<?php

if($_SERVER["REQUEST_METHOD"] === "POST"){
    $username = $_POST["username"];
    $pwd = $_POST["pwd"];

    try {
        require_once 'dbh.inc.php';
        require_once 'model/login_model.inc.php';
        require_once 'controller/login_controller.inc.php';

        //ERROR HANDLERS
        $errors = [];

        if (is_input_empty($username, $pwd)) {
            $errors["empty_input"] = "fill in all fields";
        }
      
        $result = get_user($pdo, $username);

        if (is_username_wrong($result)) {
            $errors["wrong_username"] = "wrong username";
        }
        if (is_username_wrong($result) && is_password_wrong($pwd, $result["pwd"])) {
            $errors["invalid_login"] = "login details invalid";
        }

        require_once 'config/config_session.inc.php';

        if ($errors) {
            $_SESSION["errors_login"] = $errors;

            header("Location: ../index.php");
            die();
        }

        $_SESSION["user_id"] = $result["id"];
        $_SESSION["user_username"] = htmlspecialchars($result["username"]);

        $_SESSION["last_regeneration"] = time();

        header("Location: ../index.php?login=success");
        $pdo = null;
        $stmt = null;

        die();

    } catch (PDOException $e) {
        die("Query failed: ". $e->getMessage());
    }
}else{
    header("Location: ../index.php");
    die();
}
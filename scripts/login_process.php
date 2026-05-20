<?php
declare(strict_types=1);

session_start();

require_once "../../Services/AuthService.php";



$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = trim($_POST["email"] ?? '');
    $password = trim($_POST["password"] ?? '');

    $user = $auth->login($email, $password);

    if ($user) {

        $_SESSION["user"] = $user;

        header("Location: ../dashboard.php");
        exit;

    } else {

        $error = "Invalid email or password";

    }
}
?>
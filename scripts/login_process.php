<?php
session_start();

require_once __DIR__ . "/../config/Database.php";
require_once __DIR__ . "/../src/repository/UserRepository.php";

$pdo = Database::getInstance();
$userRepo = new UserRepository($pdo);

$error = "";




if (isset($_SESSION["user"])) {
    header("Location: ../public/dashboard.php");
    exit;
}


if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    if (!empty($email) && !empty($password)) {

        $user = $userRepo->findByEmail($email);

        if ($user && password_verify($password, $user->password)) {

            $_SESSION["user"] = $user;

            header("Location: ../public/dashboard.php");
            exit;

        } else {
            $error = "Email ou mot de passe incorrect";
        }

    } else {
        $error = "Veuillez remplir tous les champs";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Login - PeerSync</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gradient-to-r from-blue-600 to-blue-400 min-h-screen flex items-center justify-center">

<div class="bg-white w-full max-w-md p-8 rounded-2xl shadow-lg">

    <h1 class="text-3xl font-bold text-center text-blue-600 mb-6">
      Login Apprenant
    </h1>

 
    <?php if (!empty($error)) : ?>
        <div class="bg-red-100 text-red-700 p-3 rounded mb-4 text-center">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <form method="POST" class="space-y-5">

        <input type="email"
               name="email"
               required
               placeholder="Email"
               class="w-full border p-3 rounded-lg">

        <input type="password"
               name="password"
               required
               placeholder="Mot de passe"
               class="w-full border p-3 rounded-lg">

        <button type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-lg font-semibold">
            Se connecter
        </button>

    </form>

    <p class="text-center mt-6 text-gray-600">
        Pas de compte ?
        <a href="register.php" class="text-blue-600 font-semibold">
            S'inscrire
        </a>
    </p>

</div>

</body>
</html>
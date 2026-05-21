<?php
session_start();
require_once __DIR__ . "/../config/Database.php";

$pdo = Database::getInstance();

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    $stmt = $pdo->prepare("SELECT * FROM apprenants WHERE email = :email");
    $stmt->execute([":email" => $email]);

    $user = $stmt->fetch(PDO::FETCH_OBJ);

    if ($user && password_verify($password, $user->password)) {

        $_SESSION["user"] = $user;

        header("Location: ../public/dashboard.php");
        exit;

    } else {
        $error = "Email ou mot de passe incorrect";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Apprenant</title>

    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 flex items-center justify-center min-h-screen">

    <div class="bg-white p-8 rounded-2xl shadow-lg w-full max-w-md">

        <h1 class="text-3xl font-bold text-center text-blue-600 mb-6">
            Login Apprenant
        </h1>

        <?php if (!empty($error)) : ?>

            <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
                <?= htmlspecialchars($error) ?>
            </div>

        <?php endif; ?>

        <form method="POST" class="space-y-5">

            <div>
                <label class="block mb-2 font-medium">
                    Email
                </label>

                <input
                    type="email"
                    name="email"
                    required
                    class="w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
            </div>

            <div>
                <label class="block mb-2 font-medium">
                    Mot de passe
                </label>

                <input
                    type="password"
                    name="password"
                    required
                    class="w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
            </div>

            <button
                type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-lg font-semibold transition"
            >
                Se connecter
              
            </button>

        </form>

        <p class="text-center mt-6 text-gray-600">
            Vous n'avez pas de compte ?

            <a
                href="regester.php"
                class="text-blue-600 font-semibold hover:underline"
            >
                S'inscrire
            </a>
        </p>

    </div>

</body>

</html>
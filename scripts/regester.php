<?php
declare(strict_types=1);

session_start();

require_once __DIR__ . "/../config/Database.php";

$pdo = Database::getInstance();

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nom = trim($_POST["nom"]);
    $prenom = trim($_POST["prenom"]);
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    
    $check = $pdo->prepare("SELECT id FROM apprenants WHERE email = :email");
    $check->execute([":email" => $email]);

    if ($check->fetch()) {
        $error = "Cet email existe deja.";
    } else {

        $hash = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO apprenants (nom, prenom, email, password)
                VALUES (:nom, :prenom, :email, :password)";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            ":nom" => $nom,
            ":prenom" => $prenom,
            ":email" => $email,
            ":password" => $hash
        ]);

        $success = "Inscription reussie  Vous pouvez vous connecter.";
      
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Register Apprenant</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 flex items-center justify-center min-h-screen">

<div class="bg-white p-8 rounded-2xl shadow-lg w-full max-w-md">

    <h1 class="text-3xl font-bold text-center text-green-600 mb-6">
        Inscription Apprenant
    </h1>

    <?php if ($error): ?>
        <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
            <?= $error ?>
        </div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
            <?= $success ?>
        </div>
    <?php endif; ?>

    <form method="POST" class="space-y-4">

        <input type="text" name="nom" placeholder="Nom"
               class="w-full p-3 border rounded" required>

        <input type="text" name="prenom" placeholder="Prénom"
               class="w-full p-3 border rounded" required>

        <input type="email" name="email" placeholder="Email"
               class="w-full p-3 border rounded" required>

        <input type="password" name="password" placeholder="Mot de passe"
               class="w-full p-3 border rounded" required>

        <button type="submit"
                class="w-full bg-green-600 hover:bg-green-700 text-white py-3 rounded">
            S'inscrire
        </button>

    </form>

    <p class="text-center mt-4 text-gray-600">
        Déjà un compte ?
        <a href="login_process.php" class="text-blue-600 font-semibold">Se connecter</a>
    </p>

</div>

</body>
</html>
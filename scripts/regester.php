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

    if (!empty($nom) && !empty($prenom) && !empty($email) && !empty($password)) {

        $check = $pdo->prepare("SELECT id FROM apprenants WHERE email = ?");
        $check->execute([$email]);

        if ($check->fetch()) {
            $error = "Cet email existe déjà.";
        } else {

            $hash = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $pdo->prepare("
                INSERT INTO apprenants (nom, prenom, email, password)
                VALUES (?, ?, ?, ?)
            ");

            $stmt->execute([
                $nom,
                $prenom,
                $email,
                $hash
            ]);

            $success = "Inscription réussie  Vous pouvez vous connecter.";
        }

    } else {
        $error = "Veuillez remplir tous les champs.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Register - PeerSync</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gradient-to-r from-green-500 to-green-400 min-h-screen flex items-center justify-center">

<div class="bg-white p-8 rounded-2xl shadow-lg w-full max-w-md">

    <h1 class="text-3xl font-bold text-center text-green-600 mb-6">
      Inscription Apprenant
    </h1>

 
    <?php if (!empty($error)) : ?>
        <div class="bg-red-100 text-red-700 p-3 rounded mb-4 text-center">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    
    <?php if (!empty($success)) : ?>
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4 text-center">
            <?= htmlspecialchars($success) ?>
        </div>
    <?php endif; ?>

    
    <form method="POST" class="space-y-4">

        <input type="text" name="nom" placeholder="Nom"
               class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-green-500" required>

        <input type="text" name="prenom" placeholder="Prénom"
               class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-green-500" required>

        <input type="email" name="email" placeholder="Email"
               class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-green-500" required>

        <input type="password" name="password" placeholder="Mot de passe"
               class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-green-500" required>

        <button type="submit"
                class="w-full bg-green-600 hover:bg-green-700 text-white py-3 rounded-lg font-semibold">
            S'inscrire
        </button>

    </form>

  
    <p class="text-center mt-4 text-gray-600">
        Déjà un compte ?
        <a href="login.php" class="text-blue-600 font-semibold hover:underline">
            Se connecter
        </a>
    </p>

</div>

</body>
</html>
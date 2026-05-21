<?php
session_start();

if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit;
}

$user = $_SESSION["user"];

$nom = $user->nom;
$prenom = $user->prenom;
$email = $user->email;

$nomComplet = $prenom . " " . $nom;

/* LOGOUT */
if (isset($_GET["logout"])) {
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">

<div class="flex min-h-screen">

    <!-- SIDEBAR -->
    <aside class="w-64 bg-blue-700 text-white p-5">

        <h2 class="text-xl font-bold mb-6">
             <?= htmlspecialchars($nomComplet) ?>
        </h2>

        <nav class="space-y-3">

            

            <a href="profil.php" class="block p-2 hover:bg-blue-600 rounded">
                Profile
            </a>

            <a href="request_detail.php" class="block p-2 hover:bg-blue-600 rounded">
                Demandes
            </a>

            <a href="?logout=1" class="block p-2 bg-red-500 hover:bg-red-600 rounded">
                Logout
            </a>

        </nav>

    </aside>

   
    <main class="flex-1 p-8">

        <div class="bg-white p-6 rounded-2xl shadow">

            <h1 class="text-2xl font-bold mb-4">
                Welcome  : <?= htmlspecialchars($nomComplet) ?>
            </h1>

     <p class="text-gray-600">
    Tu peux aider d’autres étudiants et aussi demander de l’aide quand tu en as besoin.
</p>
        </div>

    </main>

</div>

</body>
</html>
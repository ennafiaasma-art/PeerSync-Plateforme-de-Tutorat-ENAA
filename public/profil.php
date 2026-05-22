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
    <title>Profil</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen">

<div class="flex">

   
    <aside class="w-64 bg-blue-800 text-white min-h-screen p-5">

        <h2 class="text-xl font-bold mb-6">
            <?= htmlspecialchars($nomComplet) ?>
        </h2>

        <nav class="space-y-3">

            <a href="dashboard.php"
               class="block p-2 rounded hover:bg-blue-600">
                Dashboard
            </a>

            <a href="profile.php"
               class="block p-2 rounded bg-blue-600">
                 Profile
            </a>

            <a href="request_detail.php"
               class="block p-2 rounded hover:bg-blue-600">
                Demandes d’aide
            </a>

            <a href="?logout=1"
               class="block p-2 rounded bg-red-500 hover:bg-red-600">
                 Logout
            </a>

        </nav>

    </aside>

    
    <main class="flex-1 p-8">

        
        <div class="bg-white p-8 rounded-2xl shadow max-w-2xl mx-auto">

            <h1 class="text-3xl font-bold mb-6 text-blue-600">
 Mon Profil
            </h1>

           
            <div class="flex items-center gap-4 mb-6">

                <div class="w-16 h-16 bg-blue-600 text-white flex items-center justify-center rounded-full text-xl font-bold">
                    <?= strtoupper(substr($prenom, 0, 1) . substr($nom, 0, 1)) ?>
                </div>

                <div>
                    <h2 class="text-xl font-semibold">
                        <?= htmlspecialchars($nomComplet) ?>
                    </h2>

                    <p class="text-gray-500">
                        Apprenant ENAA
                    </p>
                </div>

            </div>

           
            <div class="space-y-4">

                <div class="bg-gray-100 p-4 rounded-lg">
                    <p class="text-sm text-gray-500">Nom</p>
                    <p class="font-semibold"><?= htmlspecialchars($nom) ?></p>
                </div>

                <div class="bg-gray-100 p-4 rounded-lg">
                    <p class="text-sm text-gray-500">Prénom</p>
                    <p class="font-semibold"><?= htmlspecialchars($prenom) ?></p>
                </div>

                <div class="bg-gray-100 p-4 rounded-lg">
                    <p class="text-sm text-gray-500">Email</p>
                    <p class="font-semibold"><?= htmlspecialchars($email) ?></p>
                </div>

            </div>

        </div>

    </main>

</div>

</body>
</html>
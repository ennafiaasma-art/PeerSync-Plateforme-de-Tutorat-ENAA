<?php
session_start();

if (!isset($_SESSION["user"])) {
    header("Location: ../public/login.php");
    exit;
}

$user = $_SESSION["user"];
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Dashboard Apprenant</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen">

   
    <div class="bg-blue-600 text-white p-4 flex justify-between items-center">
        <h1 class="text-xl font-bold">Dashboard Apprenant</h1>

        <div>
            <span class="mr-4">
                <?= htmlspecialchars($user->email ?? $user["email"]) ?>
            </span>

            <a href="../scripts/logout.php" class="bg-red-500 px-3 py-2 rounded">
                Logout
            </a>
        </div>
    </div>

   
    <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-6">

        
        <div class="bg-white p-6 rounded-2xl shadow">
            <h2 class="text-2xl font-bold mb-4">Mon Profil</h2>

            <p><strong>Email :</strong>
                <?= htmlspecialchars($user->email ?? $user["email"]) ?>
            </p>

            <a href="../public/profile.php"
               class="mt-4 inline-block bg-blue-600 text-white px-4 py-2 rounded">
                Voir Profil
            </a>
        </div>

       
        <div class="bg-white p-6 rounded-2xl shadow">
            <h2 class="text-2xl font-bold mb-4">Mes Demandes</h2>

            <p>Voir les détails de tes demandes d’aide.</p>

            <a href="../public/request_detail.php"
               class="mt-4 inline-block bg-green-600 text-white px-4 py-2 rounded">
                Request Detail
            </a>
        </div>

    </div>

</body>

</html>
<?php session_start(); ?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Créer Ticket</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 flex items-center justify-center min-h-screen">

<div class="bg-white p-8 rounded-xl shadow w-full max-w-lg">

    <h1 class="text-2xl font-bold mb-6 text-blue-600">
        Créer une demande d’aide
    </h1>

    <form method="POST" action="/PeerSync/scripts/request_process.php" class="space-y-4">

        <input type="text" name="titre" placeholder="Titre"
               class="w-full border p-3 rounded" required>

        <textarea name="description" placeholder="Description"
                  class="w-full border p-3 rounded" required></textarea>

        <input type="text" name="technologie" placeholder="Technologie"
               class="w-full border p-3 rounded" required>

        <button class="w-full bg-blue-600 text-white py-3 rounded">
            Envoyer
        </button>

    </form>

</div>

</body>
</html>
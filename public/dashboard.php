<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . "/../config/Database.php";
require_once __DIR__ . "/../scripts/request_process.php";
require_once __DIR__ . "/../src/enums/Status.php";

$pdo = Database::getInstance();


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
    header("Location: ../scripts/login_process.php");
    exit;
}


$stmt = $pdo->prepare("
    SELECT * FROM demander_aides
    WHERE id_apprenant = ?
    ORDER BY id DESC
");
$stmt->execute([$user->id]);

$requests = $stmt->fetchAll(PDO::FETCH_OBJ);
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

   
    <aside class="w-64 bg-blue-800 text-white p-5">

        <h2 class="text-xl font-bold mb-6">
            <?= htmlspecialchars($nomComplet) ?>
        </h2>

        <nav class="space-y-3">

            <a href="profil.php" class="block p-2 hover:bg-blue-600 rounded">
                Profile
            </a>

            <a href="request_detail.php" class="block p-2 hover:bg-blue-600 rounded">
                Ajouter une demande
            </a>

            <a href="?logout=1" class="block p-2 bg-red-500 hover:bg-red-600 rounded">
                Logout
            </a>

        </nav>

    </aside>

    
    <main class="flex-1 p-8">

        
        <div class="bg-white p-6 rounded-2xl shadow mb-6">

            <h1 class="text-2xl font-bold mb-4">
                Welcome : <?= htmlspecialchars($nomComplet) ?>
            </h1>

            <p class="text-gray-600">
                Tu peux aider d’autres étudiants et aussi demander de l’aide quand tu en as besoin.
            </p>

        </div>

        
<div class="mt-6">

    <h2 class="text-2xl font-bold mb-4">
        Mes demandes d’aide
    </h2>

    <?php if (count($requests) > 0): ?>

        <?php foreach ($requests as $r): ?>

            <div class="bg-white p-5 rounded-2xl shadow mb-4">

                <h3 class="text-xl font-bold text-blue-600 mb-2">
                    <?= htmlspecialchars($r->titre) ?>
                </h3>

                <p class="text-gray-700 mb-3">
                    <?= htmlspecialchars($r->description) ?>
                </p>

                <p class="text-sm text-gray-500 mb-3">
                    Technologie :
                    <?= htmlspecialchars($r->technologie) ?>
                </p>

                <span class="px-3 py-1 rounded text-white text-sm
                    <?= $r->status === 'EN_ATTENTE' ? 'bg-yellow-500' : '' ?>
                    <?= $r->status === 'ASSIGNE' ? 'bg-blue-500' : '' ?>
                    <?= $r->status === 'RESOLUE' ? 'bg-green-500' : '' ?>
                ">
                    <?= htmlspecialchars($r->status) ?>
                </span>

            </div>

        <?php endforeach; ?>

    <?php else: ?>

        <div class="bg-white p-4 rounded shadow">
            Aucune demande trouvée.
        </div>

    <?php endif; ?>

</div>
    </main>

</div>

</body>
</html>
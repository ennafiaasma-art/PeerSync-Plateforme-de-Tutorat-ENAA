<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . "/../config/Database.php";

$pdo = Database::getInstance();

/* SECURITY */
if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit;
}

$user = $_SESSION["user"];
$nomComplet = $user->prenom . " " . $user->nom;

/* LOGOUT */
if (isset($_GET["logout"])) {
    session_unset();
    session_destroy();
    header("Location: ../scripts/login_process.php");
    exit;
}

/* =========================
   ACTIONS (ASSIGN / RESOLVE)
========================= */
if (isset($_GET['action'], $_GET['id'])) {

    $id = (int) $_GET['id'];
    $action = $_GET['action'];

    if ($action === 'assign') {

        $stmt = $pdo->prepare("
            UPDATE demander_aides
            SET status = 'ASSIGNE',
                id_tuteur = :id_tuteur
            WHERE id = :id
        ");

        $stmt->execute([
            'id_tuteur' => $user->id ?? null,
            'id' => $id
        ]);
    }

    if ($action === 'resolve') {

    $commentaire = "Merci pour votre aide "; 

    $stmt = $pdo->prepare("
        UPDATE demander_aides
        SET status = 'RESOLUE',
            commentaire = :commentaire
        WHERE id = :id
    ");

    $stmt->execute([
        'commentaire' => $commentaire,
        'id' => $id
    ]);
}
    header("Location: dashboard.php");
    exit;
}


$stmt = $pdo->prepare("
    SELECT * FROM demander_aides
    ORDER BY id DESC
");
$stmt->execute();

$requests = $stmt->fetchAll(PDO::FETCH_OBJ);


function getStatusClass($status): string
{
    return match ($status) {
        'EN_ATTENTE' => 'bg-yellow-500',
        'ASSIGNE'    => 'bg-blue-500',
        'RESOLUE'    => 'bg-green-500',
        default      => 'bg-gray-500',
    };
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

   
    <aside class="w-64 bg-blue-800 text-white p-5">

        <h2 class="text-xl font-bold mb-6">
            <?= htmlspecialchars($nomComplet) ?>
        </h2>

        <nav class="space-y-3">

            <a href="profil.php" class="block p-2 hover:bg-blue-600 rounded">
                Profil
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
                Tu peux aider d’autres étudiants et aussi demander de l’aide.
            </p>

        </div>

       
        <div class="mt-6">

            <h2 class="text-2xl font-bold mb-4">
                Toutes les demandes
            </h2>

            <?php if (!empty($requests)): ?>

                <?php foreach ($requests as $r): ?>

                    <div class="bg-white p-5 rounded-2xl shadow mb-4">

                       
                        <h3 class="text-xl font-bold text-blue-600 mb-2">
                            <?= htmlspecialchars($r->titre) ?>
                        </h3>

                        <p class="text-gray-700 mb-3">
                            <?= htmlspecialchars($r->description) ?>
                        </p>

                        
                        <p class="text-sm text-gray-500 mb-3">
                            Technologie : <?= htmlspecialchars($r->technologie) ?>
                        </p>

                    
                        <span class="px-3 py-1 rounded text-white text-sm <?= getStatusClass($r->status) ?>">
                            <?= htmlspecialchars($r->status) ?>
                        </span>

                        
                        <?php if (!empty($r->commentaire)): ?>
                            <div class="mt-3 p-3 bg-gray-100 rounded">
                                <strong>Message  :</strong><br>
                                <?= htmlspecialchars($r->commentaire) ?>
                            </div>
                        <?php endif; ?>

                       
                        <div class="mt-4 flex gap-2">

                            <a href="dashboard.php?action=assign&id=<?= $r->id ?>"
                               class="px-3 py-1 bg-blue-600 text-white rounded">
                                Assigner
                            </a>

                            <a href="dashboard.php?action=resolve&id=<?= $r->id ?>"
                               class="px-3 py-1 bg-green-600 text-white rounded">
                                Résoudre
                            </a>

                        </div>

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
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . "/../config/Database.php";

$pdo = Database::getInstance();


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
   ACTIONS VIA GET (ASSIGNER)
========================= */
if (isset($_GET['action'], $_GET['id']) && $_GET['action'] === 'assign') {
    $id = (int) $_GET['id'];

    $stmt = $pdo->prepare("
        UPDATE demander_aides
        SET status = 'ASSIGNE',
            id_tuteur = :id_tuteur
        WHERE id = :id AND status = 'EN_ATTENTE'
    ");

    $stmt->execute([
        'id_tuteur' => $user->id ?? null,
        'id' => $id
    ]);

    header("Location: dashboard.php");
    exit;
}

/* =========================
   ACTION VIA POST (RÉSOUDRE AVEC COMMENTAIRE)
========================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'resolve') {
    $id = (int) $_POST['id'];
    
    // On récupère le commentaire écrit par l'utilisateur, ou on met un texte par défaut s'il est vide
    $commentaire = !empty(trim($_POST['commentaire'])) ? trim($_POST['commentaire']) : "Merci pour votre aide";

    $stmt = $pdo->prepare("
        UPDATE demander_aides
        SET status = 'RESOLUE',
            commentaire = :commentaire
        WHERE id = :id AND status != 'RESOLUE'
    ");

    $stmt->execute([
        'commentaire' => $commentaire,
        'id' => $id
    ]);

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
                                <strong>Message de résolution :</strong><br>
                                <?= htmlspecialchars($r->commentaire) ?>
                            </div>
                        <?php endif; ?>

                       
                        <!-- Les actions s'affichent uniquement si la demande n'est PAS résolue -->
                        <?php if ($r->status !== 'RESOLUE'): ?>
                            <div class="mt-4 pt-4 border-t border-gray-100">
                                
                                <div class="flex flex-col gap-3">
                                    
                                    <!-- Si la demande est EN ATTENTE, on propose de l'assigner -->
                                    <?php if ($r->status === 'EN_ATTENTE'): ?>
                                        <div>
                                            <a href="dashboard.php?action=assign&id=<?= $r->id ?>"
                                               class="inline-block px-4 py-2 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition font-medium text-sm">
                                                 S'assigner cette demande
                                            </a>
                                        </div>
                                    <?php endif; ?>

                                    <!-- Formulaire pour ajouter un commentaire et Clôturer -->
                                    <form action="dashboard.php" method="POST" class="bg-gray-50 p-3 rounded-xl border border-gray-200 mt-2">
                                        <input type="hidden" name="id" value="<?= $r->id ?>">
                                        <input type="hidden" name="action" value="resolve">
                                        
                                        <label class="block text-xs font-bold text-gray-600 uppercase mb-1">
                                            Ajouter un commentaire de résolution :
                                        </label>
                                        
                                        <div class="flex gap-2">
                                            <input type="text" 
                                                   name="commentaire" 
                                                   placeholder="Ex: Problème réglé en modifiant le fichier de config !" 
                                                   class="flex-1 px-3 py-1.5 text-sm rounded-lg border border-gray-300 focus:outline-none focus:border-blue-500"
                                                   required>
                                            
                                            <button type="submit" 
                                                    class="px-4 py-1.5 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-medium text-sm whitespace-nowrap">
                                                Résoudre
                                            </button>
                                        </div>
                                    </form>

                                </div>

                            </div>
                        <?php endif; ?>

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
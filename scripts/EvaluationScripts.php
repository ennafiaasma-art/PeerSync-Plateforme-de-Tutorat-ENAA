<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . "/../config/Database.php";
require_once __DIR__ . "/../src/Entities/Evaluation.php";

use App\Entities\Evaluation;

$pdo = Database::getInstance();

if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit;
}

$user = $_SESSION["user"];
$nomComplet = $user->prenom . " " . $user->nom;

$msgSuccess = $_SESSION['msg_success'] ?? null;
$msgError = $_SESSION['msg_error'] ?? null;
unset($_SESSION['msg_success'], $_SESSION['msg_error']);

if (isset($_GET['action'], $_GET['id']) && $_GET['action'] === 'assign') {
    $id = (int) $_GET['id'];

    $stmt = $pdo->prepare("
        UPDATE demander_aides
        SET status = 'ASSIGNE', id_tuteur = :id_tuteur
        WHERE id = :id AND status = 'EN_ATTENTE'
    ");
    $stmt->execute(['id_tuteur' => $user->id ?? null, 'id' => $id]);

    header("Location: dashboard.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'resolve') {
    $id = (int) $_POST['id'];
    $commentaire = !empty(trim($_POST['commentaire'])) ? trim($_POST['commentaire']) : "Merci pour votre aide";

    $stmt = $pdo->prepare("
        UPDATE demander_aides
        SET status = 'RESOLUE', commentaire = :commentaire
        WHERE id = :id AND status != 'RESOLUE'
    ");
    $stmt->execute(['commentaire' => $commentaire, 'id' => $id]);

    header("Location: dashboard.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'evaluer') {
    try {
        $evaluation = new Evaluation(
            note: (int) $_POST['note'],
            commentaire: trim($_POST['eval_commentaire']),
            tuteurId: (int) $_POST['id_tuteur'],
            apprenantId: (int) $user->id
        );

        $stmt = $pdo->prepare("
            INSERT INTO evaluations (note, commentaire, id_tuteur, id_apprenant, id_demande)
            VALUES (:note, :commentaire, :id_tuteur, :id_apprenant, :id_demande)
        ");
        
        $stmt->execute([
            'note' => $evaluation->getNote(),
            'commentaire' => $evaluation->getCommentaire(),
            'id_tuteur' => (int) $_POST['id_tuteur'],
            'id_apprenant' => $user->id,
            'id_demande' => (int) $_POST['id_demande']
        ]);

        $_SESSION['msg_success'] = "Merci ! Votre évaluation a été enregistrée avec succès.";
    } catch (Exception $e) {
        $_SESSION['msg_error'] = "Impossible d'enregistrer l'avis : " . $e->getMessage();
    }

    header("Location: dashboard.php");
    exit;
}


$stmt = $pdo->prepare("SELECT * FROM demander_aides ORDER BY id DESC");
$stmt->execute();
$requests = $stmt->fetchAll(PDO::FETCH_OBJ);

function getStatusClass($status): string {
    return match ($status) {
        'EN_ATTENTE' => 'bg-amber-500 text-white',
        'ASSIGNE'    => 'bg-blue-600 text-white',
        'RESOLUE'    => 'bg-emerald-600 text-white',
        default      => 'bg-gray-500 text-white',
    };
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Étudiant</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 font-sans text-slate-800">

<div class="flex min-h-screen">

    
    <aside class="w-64 bg-slate-900 text-white p-6 flex flex-col justify-between">
        <div>
            <div class="mb-8">
                <p class="text-xs text-slate-400 uppercase tracking-wider font-bold">Étudiant connecté</p>
                <h2 class="text-lg font-bold text-white mt-1"><?= htmlspecialchars($nomComplet) ?></h2>
            </div>

            <nav class="space-y-1">
                <a href="profil.php" class="block py-2.5 px-4 rounded-xl hover:bg-slate-800 transition font-medium text-slate-300 hover:text-white">
                    Mon Profil
                </a>
                <a href="request_detail.php" class="block py-2.5 px-4 rounded-xl hover:bg-slate-800 transition font-medium text-slate-300 hover:text-white">
                    Ajouter une demande
                </a>
            </nav>
        </div>

        <a href="?logout=1" class="block text-center py-2.5 px-4 bg-red-600 hover:bg-red-700 text-white font-medium rounded-xl transition shadow-sm">
            Déconnexion
        </a>
    </aside>

    <main class="flex-1 p-8 max-w-5xl mx-auto w-full">

        <?php if ($msgSuccess): ?>
            <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl font-medium text-sm shadow-sm">
                <?= htmlspecialchars($msgSuccess) ?>
            </div>
        <?php endif; ?>
        <?php if ($msgError): ?>
            <div class="mb-6 p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl font-medium text-sm shadow-sm">
                <?= htmlspecialchars($msgError) ?>
            </div>
        <?php endif; ?>

        <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm mb-8">
            <h1 class="text-2xl font-black tracking-tight text-slate-900 mb-1">
                Content de vous revoir, <?= htmlspecialchars($user->prenom) ?> ! 👋
            </h1>
            <p class="text-slate-500 text-sm">
                Aidez vos camarades en acceptant leurs demandes ou suivez vos propres requêtes d'assistance.
            </p>
        </div>

        <div class="space-y-5">
            <h2 class="text-xl font-bold tracking-tight text-slate-900">Toutes les demandes</h2>

            <?php if (!empty($requests)): ?>
                <?php foreach ($requests as $r): ?>
                    <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm hover:shadow-md transition duration-200">
                        
                        <div class="flex items-start justify-between gap-4 mb-3">
                            <div>
                                <span class="inline-block px-2.5 py-0.5 rounded-md text-xs font-bold uppercase <?= getStatusClass($r->status) ?>">
                                    <?= str_replace('_', ' ', $r->status) ?>
                                </span>
                                <h3 class="text-lg font-bold text-slate-900 mt-2">
                                    <?= htmlspecialchars($r->titre) ?>
                                </h3>
                            </div>
                            <span class="text-xs font-bold bg-slate-100 text-slate-600 px-3 py-1 rounded-full">
                                <?= htmlspecialchars($r->technologie) ?>
                            </span>
                        </div>

                        <p class="text-slate-600 text-sm leading-relaxed mb-4">
                            <?= htmlspecialchars($r->description) ?>
                        </p>

                        <?php if (!empty($r->commentaire)): ?>
                            <div class="p-4 bg-slate-50 rounded-xl border border-slate-100 text-sm mb-4">
                                <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Solution apportée</span>
                                <p class="text-slate-700 font-medium"><?= htmlspecialchars($r->commentaire) ?></p>
                            </div>
                        <?php endif; ?>

                        <?php if ($r->status !== 'RESOLUE'): ?>
                            
                            <div class="mt-4 pt-4 border-t border-slate-100 flex flex-col gap-4">
                                <?php if ($r->status === 'EN_ATTENTE'): ?>
                                    <div>
                                        <a href="dashboard.php?action=assign&id=<?= $r->id ?>" 
                                           class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl transition shadow-sm">
                                            Prendre en charge cette demande
                                        </a>
                                    </div>
                                <?php endif; ?>

                                <form action="dashboard.php" method="POST" class="bg-slate-50 p-4 rounded-xl border border-slate-200/60">
                                    <input type="hidden" name="id" value="<?= $r->id ?>">
                                    <input type="hidden" name="action" value="resolve">
                                    
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">
                                        Expliquez brièvement comment le problème a été réglé :
                                    </label>
                                    <div class="flex flex-col sm:flex-row gap-2">
                                        <input type="text" name="commentaire" placeholder="Ex: Correction de la route d'API et redémarrage du serveur..." 
                                               class="flex-1 px-4 py-2 text-sm bg-white rounded-xl border border-slate-200 focus:outline-none focus:border-blue-500" required>
                                        <button type="submit" class="px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-xl transition whitespace-nowrap">
                                            Marquer comme résolu
                                        </button>
                                    </div>
                                </form>
                            </div>

                        <?php else: ?>
                            
                            <?php 
                            $idTuteur = !empty($r->id_tuteur) ? (int)$r->id_tuteur : 0;
                            if ($idTuteur > 0 && $idTuteur !== (int)$user->id): 
                            ?>
                                <div class="mt-4 pt-4 border-t border-slate-100">
                                    <form action="dashboard.php" method="POST" class="bg-amber-50/50 border border-amber-200/60 p-4 rounded-xl">
                                        <input type="hidden" name="action" value="evaluer">
                                        <input type="hidden" name="id_demande" value="<?= (int) $r->id ?>">
                                        <input type="hidden" name="id_tuteur" value="<?= $idTuteur ?>">

                                        <h4 class="text-sm font-bold text-amber-900 mb-1">⭐ Noter l'aide reçue</h4>
                                        <p class="text-xs text-amber-700 mb-3">Laissez votre avis sur le travail du tuteur. La note saisie doit être comprise entre 1 et 5.</p>

                                        <div class="grid grid-cols-1 md:grid-cols-4 gap-3 items-end">
                                           
                                            <div class="md:col-span-1">
                                                <label class="block text-[11px] font-bold text-slate-400 uppercase mb-1">Note globale</label>
                                                <select name="note" class="w-full text-sm px-3 py-2 bg-white border border-slate-200 rounded-xl focus:outline-none focus:ring-1 focus:ring-amber-500" required>
                                                    <option value="5">⭐⭐⭐⭐⭐ (5/5)</option>
                                                    <option value="4">⭐⭐⭐⭐ (4/5)</option>
                                                    <option value="3">⭐⭐⭐ (3/5)</option>
                                                    <option value="2">⭐⭐ (2/5)</option>
                                                    <option value="1">⭐ (1/5)</option>
                                                </select>
                                            </div>

                                           
                                            <div class="md:col-span-2">
                                                <label class="block text-[11px] font-bold text-slate-400 uppercase mb-1">Commentaire</label>
                                                <input type="text" name="eval_commentaire" placeholder="Ex: Super explications, patient et clair !" 
                                                       class="w-full text-sm px-4 py-2 bg-white border border-slate-200 rounded-xl focus:outline-none focus:ring-1 focus:ring-amber-500" required>
                                            </div>

                                  
                                            <div class="md:col-span-1">
                                                <button type="submit" class="w-full py-2 bg-amber-500 hover:bg-amber-600 text-white font-semibold text-sm rounded-xl transition">
                                                    Envoyer la note
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            <?php endif; ?>

                        <?php endif; ?>

                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="bg-white p-6 rounded-2xl border border-slate-200 text-center text-slate-400 text-sm">
                    Aucune demande trouvée pour le moment.
                </div>
            <?php endif; ?>
        </div>

    </main>
</div>

</body>
</html>
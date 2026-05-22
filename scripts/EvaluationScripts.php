<?php

require_once "../src/Entities/Evaluation.php";

use App\Entities\Evaluation;

try {

    $evaluation = new Evaluation(
        note: 4,
        commentaire: "Très bon tuteur",
        tuteurId: 2,
        apprenantId: 5
    );

    echo "Evaluation créée avec succès.";

} catch (Exception $e) {

    echo "Erreur : " . $e->getMessage();
}
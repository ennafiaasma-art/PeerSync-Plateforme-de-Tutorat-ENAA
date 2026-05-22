<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . "/../config/Database.php";
require_once __DIR__ . "/../src/enums/Status.php";

use App\enums\Status;

$pdo = Database::getInstance();

if (!isset($_SESSION["user"])) {
    header("Location: ../public/login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $titre = $_POST["titre"];
    $description = $_POST["description"];
    $technologie = $_POST["technologie"];

    $userId = $_SESSION["user"]->id;

    $sql = "INSERT INTO demander_aides
            (titre, description, technologie, status, id_apprenant)
            VALUES (?, ?, ?, ?, ?)";

    $stmt = $pdo->prepare($sql);

   $stmt->execute([
    $titre,
    $description,
    $technologie,
    Status::EN_ATTENTE,
    $userId
]);

    header("Location: ../public/dashboard.php");
    exit;
}
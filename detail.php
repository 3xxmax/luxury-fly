<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

$host = "localhost"; $dbname = "luxury_fly";
$user = "root";      $password = "";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["erreur" => $e->getMessage()]);
    exit;
}

$id = isset($_GET["id"]) ? (int)$_GET["id"] : 0;

if ($id <= 0) {
    http_response_code(400);
    echo json_encode(["erreur" => "ID invalide"]);
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM voyage WHERE id = :id");
$stmt->execute([":id" => $id]);
$voyage = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$voyage) {
    http_response_code(404);
    echo json_encode(["erreur" => "Voyage introuvable"]);
    exit;
}

$stmtImg = $pdo->prepare("SELECT image FROM voyage_image WHERE voyage_id = :id");
$stmtImg->execute([":id" => $id]);
$voyage["images"] = $stmtImg->fetchAll(PDO::FETCH_COLUMN);

echo json_encode($voyage);
?>
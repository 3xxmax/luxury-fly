<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

$host     = "localhost";
$dbname   = "luxury_fly";
$user     = "root";
$password = "";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["erreur" => $e->getMessage()]);
    exit;
}

// Récupère tous les voyages avec leur première image
$stmt = $pdo->query("
    SELECT v.id, v.libelle, v.prix, v.description,
           (SELECT vi.image FROM voyage_image vi WHERE vi.voyage_id = v.id LIMIT 1) AS image
    FROM voyage v
    ORDER BY v.id ASC
");

echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
?>
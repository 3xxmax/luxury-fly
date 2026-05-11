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

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    echo json_encode(["erreur" => "Méthode non autorisée"]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);

$nom         = trim($data["nom"]         ?? "");
$prenom      = trim($data["prenom"]      ?? "");
$email       = trim($data["email"]       ?? "");
$destination = trim($data["destination"] ?? "");
$date_depart = trim($data["date_depart"] ?? "");
$date_retour = trim($data["date_retour"] ?? "");
$passagers   = (int)($data["passagers"]  ?? 1);

// Validation
$erreurs = [];
if (empty($nom))                                      $erreurs[] = "Nom requis";
if (empty($prenom))                                   $erreurs[] = "Prénom requis";
if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL))
                                                      $erreurs[] = "Email invalide";
if (empty($destination))                              $erreurs[] = "Destination requise";
if (empty($date_depart))                              $erreurs[] = "Date de départ requise";
if (empty($date_retour))                              $erreurs[] = "Date de retour requise";
if ($date_retour <= $date_depart)                     $erreurs[] = "La date de retour doit être après le départ";
if ($passagers < 1 || $passagers > 20)               $erreurs[] = "Nombre de passagers invalide";

if (!empty($erreurs)) {
    http_response_code(400);
    echo json_encode(["erreur" => implode(", ", $erreurs)]);
    exit;
}

$stmt = $pdo->prepare("
    INSERT INTO ticket (nom, prenom, email, destination, date_depart, date_retour, passagers)
    VALUES (:nom, :prenom, :email, :destination, :date_depart, :date_retour, :passagers)
");
$stmt->execute([
    ":nom"         => $nom,
    ":prenom"      => $prenom,
    ":email"       => $email,
    ":destination" => $destination,
    ":date_depart" => $date_depart,
    ":date_retour" => $date_retour,
    ":passagers"   => $passagers,
]);

$idResa = $pdo->lastInsertId();

echo json_encode([
    "succes"      => true,
    "id"          => $idResa,
    "nom"         => $nom,
    "prenom"      => $prenom,
    "email"       => $email,
    "destination" => $destination,
    "date_depart" => $date_depart,
    "date_retour" => $date_retour,
    "passagers"   => $passagers,
]);
?>
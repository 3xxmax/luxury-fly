<?php

$host     = "localhost";
$dbname   = "luxury_fly";
$user     = "root";       // ton user MySQL
$password = "";           // ton mot de passe MySQL

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Vérification que le formulaire a bien été envoyé en POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: contact.html");
    exit;
}

// Récupération et nettoyage des données
$nom    = trim($_POST["nom"]    ?? "");
$prenom = trim($_POST["prenom"] ?? "");
$mail   = trim($_POST["mail"]   ?? "");
$sujet  = trim($_POST["sujet"]  ?? "");
$message = trim($_POST["message"] ?? "");

// Validation basique
$erreurs = [];

if (empty($nom))     $erreurs[] = "Le nom est requis.";
if (empty($prenom))  $erreurs[] = "Le prénom est requis.";
if (empty($mail) || !filter_var($mail, FILTER_VALIDATE_EMAIL))
                     $erreurs[] = "L'adresse email est invalide.";
if (empty($message)) $erreurs[] = "Le message est requis.";

// Validation de l'ENUM
$sujets_valides = ["PB_réservation", "PB_ticket", "question", "autre"];
if (!in_array($sujet, $sujets_valides)) $erreurs[] = "Sujet invalide.";

// S'il y a des erreurs, on retourne sur le formulaire
if (!empty($erreurs)) {
    $liste = implode("<br>", $erreurs);
    die("
        <p style='color:red; font-family:Arial;'>Erreurs :<br>$liste</p>
        <a href='javascript:history.back()'>← Retour</a>
    ");
}

// Insertion en base de données
try {
    $stmt = $pdo->prepare("
        INSERT INTO message (nom, prénom, sujet, mail, message, date)
        VALUES (:nom, :prenom, :sujet, :mail, :message, NOW())
    ");

    $stmt->execute([
        ":nom"     => $nom,
        ":prenom"  => $prenom,
        ":sujet"   => $sujet,
        ":mail"    => $mail,
        ":message" => $message,
    ]);

    // Redirection vers une page de confirmation
    header("Location: contact.html?succes=1");
    exit;

} catch (PDOException $e) {
    die("Erreur lors de l'envoi : " . $e->getMessage());
}
?>
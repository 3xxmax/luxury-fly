<?php
// Connexion à la base de données
$host = "localhost"; // Hôte MySQL
$user = "root"; // Nom d'utilisateur MySQL
$password = ""; // Mot de passe MySQL (laisser vide si aucun mot de passe)

$dbname = "ctv"; // Nom de la base de données

$conn = new mysqli($host, $user, $password, $dbname);

// Vérifie la connexion
if ($conn->connect_error) {
    die("Échec de la connexion : " . $conn->connect_error);
}

// Vérifie si les données du formulaire sont bien envoyées
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = $conn->real_escape_string($_POST['nom']);
    $email = $conn->real_escape_string($_POST['email']);
    $sujet = $conn->real_escape_string($_POST['Sujet']);
    $message = $conn->real_escape_string($_POST['message']);

    // Requête d'insertion sécurisée
    $sql = "INSERT INTO mes (nom, mail, sujet, message) VALUES (?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $nom, $email, $sujet, $message);
    
    if ($stmt->execute()) {
        echo "✅ Message envoyé avec succès.";
    } else {
        echo "❌ Erreur lors de l'envoi du message : " . $stmt->error;
    }

    // Ferme la requête et la connexion
    $stmt->close();
} else {
    echo "❌ Requête invalide.";
}

$conn->close();
?>

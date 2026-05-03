<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="contact.css">
</head>
<body>
<?php
  

$servername = "localhost"; //mettre le nom de VOTRE serveur 
$username = "root"; //mettre VOTRE identifiant 
$password = ""; //mettre VOTRE mot de passe 
$dbname = "ctv"; //mettre le nom de VOTRE base de données 

$conn = mysqli_connect($servername, $username, $password, $dbname);

// Vérifier la connexion
if (!$conn) {
  die("La connexion à la base de données a échoué : " . mysqli_connect_error());
}


$nom=$_POST['nom'];
$email=$_POST['email'];
$sujet=$_POST['sujet'];
$message=$_POST['message'];




$sql="INSERT INTO ctv (nom, email , sujet , message )
values ("$nom", '$email', '$sujet', '$message' )"= . $_POST ['nom', 'email']
if($conn->query($sql)===TRUE){
  echo"réservation enregistrée avec succées!"
}
else{
  echo" Erreur :". $conn->error;
}
;
mysqli_close($conn);


    ?>






<li><a href="voyage html.html"> Accueil </a></li>
    <section class="res"> <li><a href="reservation.html"> Réservation </a></li></section>
   <section class="con"><li><a href="new php contact.php"> contact </a></li></section>

    <header>
        <h1>XTRAVEL - Formulaire</h1>
    </header>

    <section class="reservation-form">
        <h2>Contactez-nous</h2>
        <form action="reservation_process.php" method="POST">
            <label for="nom">Nom :</label>
            <input type="text" id="nom" name="nom" required>

            <label for="mail">Email :</label>
            <input type="mail" id="mail" name="mail" required>

            <label for="Sujet">Sujet :</label>
            <select id="Sujet" name="Sujet" required>
                <option value="paris">Probleme de reservation</option>
                <option value="new-york">Avis</option>
                <option value="tokyo">Autre</option>
            </select>

            <label for="Message">Message:</label>
            <input type="text" id="message" name="message" required>

            <button type="submit">Nous contacter</button>
        </form>
    </section>













    

</body>
</html>

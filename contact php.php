<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
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
    
</body>
</html>
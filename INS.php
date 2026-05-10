<!DOCTYPE html>
<html lang="fr">
<head>
<meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
<title>Lecture de la table article</title>
<style type="text/css" >
table {  height:10px; border-style:double;border-width: 1px;border-color:bisque ;background-color:
white;}
</style>
</head>
<body>
<?php
    define("HOST","localhost");
    define("USER","root");
    define("PASS","");
    define("PORT",3306);    

function connexobjet($base,$param)
{
    
    $idcom = new mysqli(HOST,USER,PASS,$base, PORT); 
    if (!$idcom) 
    {
        echo "<script type=text/javascript>";
        echo "alert('Connexion impossible à la base')</script>";        
        exit(); 
    }
    return $idcom; 
} 
$idcom=connexobjet("connexion","myparam"); 

$id = $_POST['id'];
$nom = $_POST['nom'];
$prenom = $_POST['pren'];
$contact = $_POST['contact'];
$logi = $_POST['logi'];
$passe = $_POST['passe'];

$sql = "INSERT INTO user (id_user, nom, prenom, contact, login, password) 
        VALUES ('$id', '$nom', '$prenom', '$contact', '$logi', '$passe')";

    if ($idcom->query($sql)) {
       header("Location: connexion.html"); 
       exit();
    }
     else {
        echo "Erreur : " . $idcom->error;
    }

$idcom->close(); 
?>
</body>
</html>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
<title>Lecture de la liste des utilisateurs</title>
<style type="text/css" >
body{
    background:white;
    display:grid;
    place-items:center;
    height:10vh;
}

table {  
    height:10px;
    border-style: none;
    border-width: 1px;
    border-color:white ;
    background-color:white;
    box-shadow: 1px 1px 11px rgb(151, 149, 149);
    
}

div{
    padding-top:30px;
}
th{
    
color:bisque;
height:20px;
width: 120px;

}

tr{
    border-style: unset;
}

.btn{
    padding: 10px 28px;
    border: none;
    cursor: pointer;
    font-size: 0.95rem;
    font-weight: bold;
    background: #0d0d0d;
    color: #fcf9f9;
    border: 1px solid bisque;
}
</style>
</head>
<body>

<?php
    include("exemple15-2.php"); 
    $idcom=connexobjet("moiii","myparam"); 


    $requete="SELECT * FROM client ORDER BY id_client"; 
    $result=$idcom->query($requete); 
    if(!$result) 
    {
    echo "Lecture impossible"; 
    }
    else 
    {
    echo "<h3>LISTE DES CLIENTS</h3>";
    echo "<table border=\"1\">";
    echo 
    "<tr><th>
    id_client</th> 
    <th>Nom</th> 
    <th>Prénom</th>
    <th>Age</th>
    <th>Ville</th>
    <th>Adresse</th>
    <th>e-Mail</th></tr>";
    while($ligne=$result->fetch_array(MYSQLI_NUM)) 
    {
    echo "<tr>";
    foreach($ligne as $valeur) 
    {
    echo "<td> $valeur </td>";
    }
    echo "</tr>";
    }
    echo "</table>";
    }
    $result->free(); 
    $idcom->close();
?>

<div>
    <a href="bienvenu.html"><input type="submit" class="btn" value="Quitter"></a>
</div>
</body>
</html>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
<title>Liste vente</title>
<style type="text/css">
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
width: 130px;

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
 <a href="effectuer.html"><input type="submit" class="btn" value="Nouvelle vente" style="background:#fbd6a3;;"></a>
<?php
include("exemple15-2.php");
$idcom = connexobjet("moiii", "myparam");
 


 
$requete = "SELECT cl.nom, cl.prenom, a.id_article, a.design, a.prix, c.qte_comm,
                   (a.prix * c.qte_comm) AS montant
            FROM cointenir c
            JOIN article  a  ON c.id_article = a.id_article
            JOIN commande co ON c.id_comm    = co.id_comm
            JOIN client   cl ON co.id_client = cl.id_client";
 
$result = $idcom->query($requete);
 
if (!$result) {
    echo "Lecture impossible : " . $idcom->error;
} else {
    $nb = $result->num_rows;
    echo "<h4>$nb vente(s) éffectuée(s)</h4>";
 
    echo "<table border=\"1\">";
    echo "<tr>
            <th>Nom</th>
            <th>Prénom</th>
            <th>ID Article</th>
            <th>Désignation</th>
            <th>Prix unitaire</th>
            <th>Quantité</th>
            <th>Montant</th>
          </tr>";
 
    while ($ligne = $result->fetch_array(MYSQLI_NUM)) {
        echo "<tr>";
        foreach ($ligne as $valeur) {
            echo "<td> $valeur </td>";
        }
        echo "</tr>";
    }
 
    
    $result->free();
}
 
$idcom->close();
?>
 
<br>

 <a href="bienvenu.html"><button class="btn">Quitter</button></a>
</body>
</html>
 
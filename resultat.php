<!DOCTYPE html>
<html lang="fr">
<head>
<meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
<title>Résultat de la vente</title>
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
width: 200px;

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
$idcom = connexobjet("moiii", "myparam");
 
$nom      = $_POST['nom'];
$prenom   = $_POST['prenom'];
$age      = $_POST['age'];
$adresse  = $_POST['adresse'];
$ville    = $_POST['ville'];
$qtes     = $_POST['qte'];      
$articles = $_POST['articles']; 
$sql_client = "INSERT INTO client(nom, prenom, age, adresse, ville)
               VALUES ('$nom', '$prenom', '$age', '$adresse', '$ville')";
 
if ($idcom->query($sql_client)) {
    $idclient = $idcom->insert_id;
} else {
    echo "Erreur insertion client : " . $idcom->error;
    $idcom->close();
    exit;
}
 

$montant_total = 0;
foreach ($qtes as $id_article => $qte) {
    $qte = intval($qte);
    if ($qte > 0 && isset($articles[$id_article])) {
        $prix           = floatval($articles[$id_article]['prix']);
        $montant_total += $prix * $qte;
    }
}
 

$date = date('Y-m-d');
$sql_commande = "INSERT INTO commande(date, montant, id_client)
                 VALUES ('$date', '$montant_total', '$idclient')";
 
if ($idcom->query($sql_commande)) {
    $idcomm = $idcom->insert_id;
} else {
    echo "Erreur insertion commande : " . $idcom->error;
    $idcom->close();
    exit;
}
 

foreach ($qtes as $id_article => $qte) {
    $qte = intval($qte);
    if ($qte > 0 && isset($articles[$id_article])) {
        $sql_contenir = "INSERT INTO cointenir(qte_comm, id_article, id_comm)
                         VALUES ('$qte', '$id_article', '$idcomm')";
        if (!$idcom->query($sql_contenir)) {
            echo "Erreur insertion cointenir : " . $idcom->error . "<br>";
        }
    }
}
 

echo "<h3>Récapitulatif de la vente</h3>";
echo "<p><strong>Client :</strong> $nom $prenom &nbsp;|&nbsp;
         <strong>Âge :</strong> $age &nbsp;|&nbsp;
         <strong>Adresse :</strong> $adresse, $ville</p>";
echo "<br>";
 
$requete = "SELECT cl.nom, cl.prenom, a.id_article, a.design, a.prix, c.qte_comm,
                   (a.prix * c.qte_comm) AS montant
            FROM cointenir c
            JOIN article  a  ON c.id_article = a.id_article
            JOIN commande co ON c.id_comm    = co.id_comm
            JOIN client   cl ON co.id_client = cl.id_client
            WHERE c.id_comm = '$idcomm'";
 
$result = $idcom->query($requete);
 
if (!$result) {
    echo "Lecture impossible : " . $idcom->error;
} else {
    $nb = $result->num_rows;
    echo "<h4>$nb article(s) acheté(s)</h4>";
 
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
 
    echo "<tr>
            <td colspan=\"6\"><strong>TOTAL</strong></td>
            <td><strong>$montant_total FCFA</strong></td>
          </tr>";
 
    echo "</table>";
    $result->free();
}
 
$idcom->close();
?>
 
<br>
<a href="effectuer.html">← Nouvelle vente</a>
 
</body>
</html>
 
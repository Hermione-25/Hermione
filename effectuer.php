<!DOCTYPE html>
<html lang="fr">
<head>
<meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
<title>Résultat de la vente</title>
<style type="text/css">
table {
    border-style: double;
    border-width: 1px;
    border-color: bisque;
    background-color: white;
}
</style>
</head>
<body>

<?php
include("exemple15-2.php");
$idcom = connexobjet("moiii", "myparam");

// ── Récupération des données client ──────────────────────────────
$nom      = $_POST['nom'];
$prenom   = $_POST['prenom'];
$age      = $_POST['age'];
$adresse  = $_POST['adresse'];
$ville    = $_POST['ville'];
$qtes     = $_POST['qte'];      // tableau : ['ART001' => 2, 'ART002' => 0, ...]
$articles = $_POST['articles']; // tableau : ['ART001' => ['design'=>..., 'prix'=>...], ...]

// ── 1. Insérer le client ─────────────────────────────────────────
$sql_client = "INSERT INTO client(nom, prenom, age, adresse, ville)
               VALUES ('$nom', '$prenom', '$age', '$adresse', '$ville')";

if ($idcom->query($sql_client)) {
    $idclient = $idcom->insert_id;
} else {
    echo "Erreur insertion client : " . $idcom->error;
    $idcom->close();
    exit;
}

// ── 2. Calculer le montant total ──────────────────────────────────
$montant_total = 0;
foreach ($qtes as $idarticle => $qte) {
    $qte = intval($qte);
    if ($qte > 0 && isset($articles[$idarticle])) {
        $prix          = floatval($articles[$idarticle]['prix']);
        $montant_total += $prix * $qte;
    }
}

// ── 3. Insérer la commande ────────────────────────────────────────
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

// ── 4. Insérer dans contenir (un enregistrement par article choisi) ──
foreach ($qtes as $idarticle => $qte) {
    $qte = intval($qte);
    if ($qte > 0 && isset($articles[$idarticle])) {
        $sql_contenir = "INSERT INTO contenir(qte, idarticle, idcomm)
                         VALUES ('$qte', '$idarticle', '$idcomm')";
        if (!$idcom->query($sql_contenir)) {
            echo "Erreur insertion contenir : " . $idcom->error . "<br>";
        }
    }
}

// ── 5. Afficher le récapitulatif ──────────────────────────────────
echo "<h3>Récapitulatif de la vente</h3>";
echo "<p><strong>Client :</strong> $nom $prenom &nbsp;|&nbsp;
         <strong>Âge :</strong> $age &nbsp;|&nbsp;
         <strong>Adresse :</strong> $adresse, $ville</p>";
echo "<br>";

// Requête : on joint contenir + article pour n'afficher que les articles achetés
$requete = "SELECT cl.nom, cl.prenom, a.idarticle, a.design, a.prix, c.qte,
                   (a.prix * c.qte) AS montant
            FROM cointenir c
            JOIN article  a  ON c.idarticle = a.idarticle
            JOIN commande co ON c.idcomm    = co.idcomm
            JOIN client   cl ON co.idclient = cl.idclient
            WHERE c.idcomm = '$idcomm'";

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

    // Ligne total
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

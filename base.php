<!DOCTYPE html>
<html lang="fr">
<head>
<meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
<title>Lecture de la table article</title>
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
$idcom=connexobjet("moiii","myparam"); 

$requete="SELECT * FROM article ORDER BY categorie"; 
$result=$idcom->query($requete); 
if(!$result) 
{
echo "Lecture impossible"; 
}
else 
{
$nbart=$result->num_rows; 
echo "<h3>Tous nos articles par catégorie</h3>";
echo "<h4>Il y a $nbart articles en magasin</h4>"; 
echo "<table border=\"1\">";
echo 
"<tr><th>Code 
article</th> 
<th>Description</th> 
<th>prix</th>
<th>Catégorie</th></tr>";
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

 <a href="bienvenu.html"><button >Quiter</button></a>
</body>
</html>

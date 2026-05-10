 
<?php
define("HOST", "localhost");
define("USER", "root");
define("PASS", "");
define("PORT", 3306);

function conexobjet($base, $param) {
    $idcom = new mysqli(HOST, USER, PASS, $base, PORT);
    if (!$idcom) {
        echo "Connexion BDD impossible";
        exit();
    }
    return $idcom;
}

$idcom = conexobjet("connexion", "myparam");

$login = $_POST["login"];
$passe = $_POST["passe"];


$sql = "SELECT * FROM user WHERE login='$login' and password ='$passe'";
$result = $idcom->query($sql);



if ($result->num_rows == 1) {
    $user = $result->fetch_assoc();

    if ($passe == $user['password']) {
        header("Location: bienvenu.html");
        exit();
    } else {
        echo "Mot de passe incorrect";
    }
} else {
    echo "Aucun utilisateur trouvé avec ce login";
}
?>
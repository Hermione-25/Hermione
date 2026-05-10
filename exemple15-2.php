<?php
function connexobjet($base,$param)
{
    
    include_once($param.".inc.php"); 
    $idcom = new mysqli(HOST,USER,PASS,$base, PORT); 
    if (!$idcom) 
    {
        echo "<script type=text/javascript>";
        echo "alert('Connexion impossible à la base')</script>";        
        exit(); 
    }
    return $idcom; 
}
?>
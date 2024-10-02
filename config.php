<?php
    $userName = 'henintsoa';
    $userPass = 'rahents';
    $dsn = "mysql:host=localhost;dbname=reseaux_sociaux";

    try{
        $pdo = new PDO($dsn, $userName, $userPass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

       // echo "<link rel='stylesheet' href='output.css'>";
    }
    catch(PDOException $e){
        echo "ERREUR:" . $e->getMessage();
    }
?>
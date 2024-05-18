<?php

    // Configuration de la base de données
    $dsn = 'mysql:host=75.119.138.111;dbname=gestapp';
    $username = 'root';
    $password = 'kirigaya@kazuto@lycoris@blue';

    try {
        $pdo = new PDO($dsn, $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die('Connection failed: ' . $e->getMessage());
    }

?>
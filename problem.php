<?php

require_once('./db.php');

$statut = "En attente";

// Vérifier que la méthode de requête est POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Vérifier que tous les champs sont présents
    $required_fields = ['description', 'categorie', 'userId'];
    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            die('Missing required field: ' . $field);
        }
    }

    // Gérer le téléchargement de la photo
    if (!empty($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = 'uploads/pictures/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }
        $photo_path = $upload_dir . basename($_FILES['photo']['name']);
        if (!move_uploaded_file($_FILES['photo']['tmp_name'], $photo_path)) {
            die('Failed to upload photo');
        }
    } else {
        die('Photo upload error');
    }

    // Préparer et exécuter la requête d'insertion
    $sql = 'INSERT INTO probleme (description, categorie, statut, userId, photo ) VALUES (:description, :categorie, :statut, :userId, :photo)';
    $stmt = $pdo->prepare($sql);

    $params = [
        ':description' => $_POST['description'],
        ':categorie' => $_POST['categorie'],
        ':statut' => $statut,
        ':userId' => $_POST['userId'] + 0,
        ':photo' => $photo_path


    ];

    if ($stmt->execute($params)) {
        echo 'Problem created successfully';
    } else {
        echo 'Failed to create Problem';
    }
} else {
    echo 'Invalid request method';
}
?>
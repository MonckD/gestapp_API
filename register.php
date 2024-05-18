<?php

require_once('./db.php');

// Vérifier que la méthode de requête est POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Vérifier que tous les champs sont présents
    $required_fields = ['nom', 'prenom', 'email', 'batiment', 'palier', 'chambre', 'lit', 'mdp'];
    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            die('Missing required field: ' . $field);
        }
    }

    // Gérer le téléchargement de la photo
    if (!empty($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = 'uploads/';
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

    // Crypter le mot de passe
    $hashed_password = password_hash($_POST['mdp'], PASSWORD_DEFAULT);

    // Préparer et exécuter la requête d'insertion
    $sql = 'INSERT INTO user (nom, prenom, email, batiment, palier, chambre, lit, photo, mdp) VALUES (:nom, :prenom, :email, :batiment, :palier, :chambre, :lit, :photo, :mdp)';
    $stmt = $pdo->prepare($sql);

    $params = [
        ':nom' => $_POST['nom'],
        ':prenom' => $_POST['prenom'],
        ':email' => $_POST['email'],
        ':batiment' => $_POST['batiment'],
        ':palier' => $_POST['palier'],
        ':chambre' => $_POST['chambre'],
        ':lit' => $_POST['lit'],
        ':photo' => $photo_path,
        ':mdp' => $hashed_password,
    ];

    if ($stmt->execute($params)) {
        echo 'User created successfully';
    } else {
        echo 'Failed to create user';
    }
} else {
    echo 'Invalid request method';
}
?>
<?php
require_once('./db.php');

// Vérifier que la méthode de requête est POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Vérifier que les champs email et mdp sont présents
    if (empty($_POST['email']) || empty($_POST['mdp'])) {
        die('Email and password are required.');
    }

    // Récupérer les valeurs de la requête POST
    $email = $_POST['email'];
    $password = $_POST['mdp'];

    // Préparer et exécuter la requête de sélection
    $sql = 'SELECT * FROM user WHERE email = :email';
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':email' => $email]);

    // Vérifier si l'utilisateur existe
    if ($stmt->rowCount() === 0) {
        die('Unknown email address.');
    }

    // Récupérer les informations de l'utilisateur
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Vérifier le mot de passe
    if (!password_verify($password, $user['mdp'])) {
        die('Incorrect password.');
    }

    // Si tout est correct, l'utilisateur est connecté avec succès
    echo json_encode(['message' => 'User logged in successfully.', 'id' => $user['id']]);
} else {
    echo 'Invalid request method.';
}
?>